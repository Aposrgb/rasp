<?php

namespace App\Controller;

use App\Entity\Days;
use App\Entity\Schedule;
use App\Form\ScheduleType;
use App\Repository\DaysRepository;
use App\Repository\ScheduleRepository;
use PhpParser\Node\Expr\Array_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/schedule")
 */
class ScheduleController extends AbstractController
{
    /**
     * @Route("/", name="schedule_index", methods={"GET"})
     */
    public function index(ScheduleRepository $scheduleRepository): Response
    {
        return $this->render('schedule/index.html.twig', [
            'schedules' => $scheduleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/crud", name="schedule_crud", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function crud(ScheduleRepository $scheduleRepository): Response
    {
        return $this->render('schedule/crud.html.twig', [
            'schedules' => $scheduleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="schedule_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $schedule = new Schedule();
        $form = $this->createForm(ScheduleType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($schedule);
            $entityManager->flush();

            return $this->redirectToRoute('schedule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('schedule/new.html.twig', [
            'schedule' => $schedule,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="schedule_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Schedule $schedule): Response
    {
        return $this->render('schedule/show.html.twig', [
            'schedule' => $schedule,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="schedule_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Schedule $schedule): Response
    {
        $form = $this->createForm(ScheduleType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('schedule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('schedule/edit.html.twig', [
            'schedule' => $schedule,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="schedule_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Schedule $schedule, DaysRepository $daysRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $schedule->getId(), $request->request->get('_token'))) {
            $days = new Days();
            foreach($daysRepository->findAll() as $day){
                if($day->getSchedule()->getId() == $schedule->getId()){
                    $day->setSchedule(null);
                    $days = $day;
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            if($days->getName() !=null){
                $entityManager->persist($days);
            }
            $entityManager->remove($schedule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('schedule_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/get/{id}", name="schedule_get", methods={"GET"})
     */
    public function getSchedule(Request $request, ScheduleRepository $scheduleRepository, DaysRepository $daysRepository): Response
    {
        $days = $daysRepository->findBy(array(), array('timeLesson' => 'ASC'));
        $daysArray = array();
        /**
         * @param Days[] $array
         * @param string $day
         */
        $getArrayDay = function ($array,$day) use ($request)
        {
            $daysArray= array();
            $j=1;
            foreach ($array as $item){
                if($item->getName()==$day && $item->getSchedule()->getId()==$request->get('id')){
                    $daysArray[]=$item;
                }
            }
            for($i=count($daysArray);$i<7;$i++){
                $daysArray[$i] = new Days();
            }
            $tmp ='';
            for($i=0;$i<7;$i++){
                if($daysArray[$i]->getTimeLesson()!=$j && $daysArray[$i]->getTimeLesson()!=0){
                    if($daysArray[$daysArray[$i]->getTimeLesson()-1]->getTimeLesson()==0){
                        $daysArray[$daysArray[$i]->getTimeLesson()-1] = $daysArray[$i];
                        $daysArray[$i] = new Days();
                    }
                    else{
                        $tmp=$daysArray[$daysArray[$i]->getTimeLesson()-1];
                        $daysArray[$daysArray[$i]->getTimeLesson()-1] = $daysArray[$i];
                        $daysArray[$i] = new Days();
                        $daysArray[$tmp->getTimeLesson()-1]=$tmp;
                    }
                }
                $j++;
            }
            return $daysArray;
        };
        $daysArray[]=$getArrayDay($days,'Понедельник');
        $daysArray[]=$getArrayDay($days,'Вторник');
        $daysArray[]=$getArrayDay($days,'Среда');
        $daysArray[]=$getArrayDay($days,'Четверг');
        $daysArray[]=$getArrayDay($days,'Пятница');
        $daysArray[]=$getArrayDay($days,'Суббота');

        return $this->render('schedule/get.twig', [
            'scheduleName' => $scheduleRepository->find($request->get('id'))->getName(),
            'monday' => $daysArray[0],
            'tuesday'=> $daysArray[1],
            'wednesday'=> $daysArray[2],
            'thursday'=> $daysArray[3],
            'friday'=> $daysArray[4],
            'saturday'=> $daysArray[5],
        ]);
    }
}
