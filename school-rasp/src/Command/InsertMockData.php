<?php

namespace App\Command;

use App\Entity\Admin;
use App\Entity\Days;
use App\Entity\Lesson;
use App\Entity\Schedule;
use App\Entity\Teacher;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InsertMockData extends Command
{
    protected static $defaultName = 'insert:data';

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
            ->setName('insert:data')
            ->setDescription("Insert new data")
            ->setHelp('Help');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataTeacher = [
            "Владимир Владимирович",
            "Петр Сергеич",
            "Евгений Хуманов",
            "Антон Пудиков",
            "Максон Крутой",
            "Валерий Констатинович",
            "Екатерина Поминова",
            "Олег Жигалев",
        ];
        $dataLesson = [
            "Математика",
            "Русский язык",
            "Литература",
            "Физика",
            "Химия",
            "История",
            "Обществознание",
            "География",
        ];
        $dataSchedule = [
            "3Г",
            "11Б",
            "10А",
            "6В",
            "7У",
            "1А",
            "2Р",
            "9Г",
        ];
        $dataDays = ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'];
        for($i=0;$i<8;$i++){
            $teacher = $this->getEntity(Teacher::class, $dataTeacher[rand(0,7)]) ?? (new Teacher())->setName($dataTeacher[rand(0,7)]);
            $lesson = $this->getEntity(Lesson::class, $dataLesson[rand(0,7)]) ??  (new Lesson())->setName($dataLesson[rand(0,7)]);
            $schedule = $this->getEntity(Schedule::class, $dataSchedule[rand(0,7)]) ?? (new Schedule())->setName($dataSchedule[rand(0,7)]);
            $day = (new Days())->setName($dataDays[rand(0,5)]);
            $day->setLesson($lesson);
            $day->setSchedule($schedule);
            $day->setTeacher($teacher);
            $day->setTimeLesson(rand(1,7));

            $this->entityManager->persist($lesson);
            $this->entityManager->persist($schedule);
            $this->entityManager->persist($teacher);
            $this->entityManager->persist($day);
        }

        $this->entityManager->flush();
        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }

    private function getEntity($class, $name)
    {
        return $this->entityManager->getRepository($class)->findOneBy(["name" => $name]);
    }

}
