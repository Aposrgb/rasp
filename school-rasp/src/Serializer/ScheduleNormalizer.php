<?php


namespace App\Serializer;


use App\Entity\Days;
use App\Entity\Schedule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class ScheduleNormalizer implements ContextAwareNormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Schedule;
    }

    /**
     * @param Schedule $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'Class' => $object->getName(),
            'Lesson' => [
                'Day' => array_map(function (Days $day) {
                    if($day->getLesson()!=null && $day->getTeacher()!=null){
                        return 'Teacher:'.$day->getTeacher()->getName().', LessonTime:'.($day->getTimeLesson()+7) .
                            ':00, LessonName:'.$day->getLesson()->getName().', DayName:'.$day->getName();
                    }
                    if($day->getLesson()!=null){
                        return 'Teacher:none, LessonTime:'.($day->getTimeLesson()+7) .
                            ':00, LessonName:'.$day->getLesson()->getName().', DayName:'.$day->getName();
                    }
                    if($day->getTeacher()!=null){
                        return 'Teacher:'.$day->getTeacher()->getName().', LessonTime:'.($day->getTimeLesson()+7) .
                            ':00, LessonName:none, DayName:'.$day->getName();
                    }
                }, $object->getDays()->toArray()),
            ],
        ];
    }

}