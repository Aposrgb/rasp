<?php

namespace App\Controller;

use App\Entity\Days;
use App\Form\DaysType;
use App\Repository\DaysRepository;
use App\Repository\LessonRepository;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/days")
 */
class DaysController extends AbstractController
{
    /**
     * @Route("/", name="days_index", methods={"GET"})
     */
    public function index(DaysRepository $daysRepository): Response
    {
        return $this->render('days/index.html.twig', [
            'days' => $daysRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="days_new", methods={"GET","POST"})
     */
    public function new(Request $request, DaysRepository $daysRepository): Response
    {
        $day = new Days();
        $form = $this->createForm(DaysType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $day->setTeacher($form->get('teacher')->getData());
            $day->setLesson($form->get('lesson')->getData());
            foreach($daysRepository->findAll() as $days){
                if($day->getSchedule() === $days->getSchedule() && $day->getTimeLesson() == $days->getTimeLesson() && $day->getName()==$days->getName()){
                    return $this->renderForm('days/new.html.twig', [
                        'day' => $day,
                        'form' => $form,
                        'error' => 'The class already has a lesson at this time',
                    ]);
                }
                if($day->getLesson()==$days->getLesson() && $day->getTimeLesson() == $days->getTimeLesson() && $day->getName()==$days->getName()){
                    return $this->renderForm('days/new.html.twig', [
                        'day' => $day,
                        'form' => $form,
                        'error' => 'At this time, there is already this lesson',
                    ]);
                }
                if($day->getTeacher()==$days->getTeacher() && $day->getTimeLesson() == $days->getTimeLesson() && $day->getName()==$days->getName()){
                    return $this->renderForm('days/new.html.twig', [
                        'day' => $day,
                        'form' => $form,
                        'error' => 'The teacher has a lesson at this time',
                    ]);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($day);
            $entityManager->flush();

            return $this->redirectToRoute('days_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('days/new.html.twig', [
            'day' => $day,
            'form' => $form,
            'error' => '',
        ]);
    }

    /**
     * @Route("/{id}", name="days_show", methods={"GET"})
     */
    public function show(Days $day): Response
    {
        return $this->render('days/show.html.twig', [
            'day' => $day,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="days_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Days $day, DaysRepository $daysRepository): Response
    {
        $form = $this->createForm(DaysType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $day->setTeacher($form->get('teacher')->getData());
            $day->setLesson($form->get('lesson')->getData());
            foreach($daysRepository->findAll() as $days){
                if($day->getId() != $days->getId() && $day->getSchedule() === $days->getSchedule()
                    && $day->getTimeLesson() == $days->getTimeLesson() && $day->getName()==$days->getName()){
                    return $this->renderForm('days/new.html.twig', [
                        'day' => $day,
                        'form' => $form,
                        'error' => 'The class already has a lesson at this time',
                    ]);
                }
                if($day->getId() != $days->getId() && $day->getLesson()==$days->getLesson() &&
                    $day->getTimeLesson() == $days->getTimeLesson() && $day->getName()==$days->getName()){
                    return $this->renderForm('days/new.html.twig', [
                        'day' => $day,
                        'form' => $form,
                        'error' => 'At this time, there is already this lesson',
                    ]);
                }
                if($day->getId() != $days->getId() && $day->getTeacher()==$days->getTeacher() &&
                    $day->getTimeLesson() == $days->getTimeLesson() && $day->getName()==$days->getName()){
                    return $this->renderForm('days/new.html.twig', [
                        'day' => $day,
                        'form' => $form,
                        'error' => 'The teacher has a lesson at this time',
                    ]);
                }
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('days_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('days/edit.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="days_delete", methods={"POST"})
     */
    public function delete(Request $request, Days $day): Response
    {
        if ($this->isCsrfTokenValid('delete'.$day->getId(), $request->request->get('_token'))) {
            $day->setLesson(null);
            $day->setSchedule(null);
            $day->setTeacher(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($day);
            $entityManager->flush();
            $entityManager->remove($day);
            $entityManager->flush();
        }

        return $this->redirectToRoute('days_index', [], Response::HTTP_SEE_OTHER);
    }
}
