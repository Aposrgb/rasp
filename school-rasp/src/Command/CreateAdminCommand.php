<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'admin:create';

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var UserPasswordEncoderInterface */
    private $userPasswordEncoder;

    public function __construct(
        ?UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('admin:create')
            ->setDescription("Create a new admin user")
            ->setHelp('This command allows you to create a user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new Admin();

        $helper = $this->getHelper('question');

        $user->setRoles(['ROLE_ADMIN']);


        $question = new Question('<question>name</question>: ');
        $question->setValidator(
            function ($answer) {
                if (!$answer) throw new RuntimeException('name is required');

                return $answer;
            }
        );
        $name = $helper->ask($input, $output, $question);
        $user->setUsername($name);

        $question = new Question('<question>password</question>: ');
        $question->setValidator(
            function ($answer) {
                if (!$answer) throw new RuntimeException('password is required');

                return $answer;
            }
        );
        $password = $helper->ask($input, $output, $question); //TODO repeat pass

        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }

}
