<?php

namespace App\Form;

use App\Entity\Days;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DaysType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, ['required' => 'true', 'label' => 'День недели',
                'choices' =>
                    ['Понедельник' => 'Понедельник', 'Вторник' => 'Вторник', 'Среда' => 'Среда',
                        'Четверг' => 'Четверг', 'Пятница' => 'Пятница', 'Суббота' => 'Суббота']
            ])
            ->add('timeLesson', ChoiceType::class, ['required' => 'true', 'label' => 'Начало урока',
                'choices' =>
                    ['8:00' => '1', '9:00' => '2', '10:00' => '3', '11:00' => '4', '12:00' => '5', '13:00' => '6', '14:00' => '7']
            ])
            ->add('schedule',null,['required' => 'true', 'label' => 'Класс',])
            ->add('teacher', null,['required' => 'true', 'label' => 'Учитель',])
            ->add('lesson', null, ['required' => 'true', 'label' => 'Предмет',])
        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Days::class,
        ]);
    }
}
