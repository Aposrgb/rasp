<?php

namespace App\Controller;

use App\Entity\Days;
use App\Entity\Lesson;
use App\Form\LessonType;
use App\Repository\DaysRepository;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lesson")
 */
class LessonController extends AbstractController
{
    /**
     * @Route("/", name="lesson_index", methods={"GET"})
     */
    public function index(LessonRepository $lessonRepository): Response
    {
        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lesson_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lesson);
            $entityManager->flush();

            return $this->redirectToRoute('lesson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lesson_show", methods={"GET"})
     */
    public function show(Lesson $lesson): Response
    {
        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lesson_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lesson $lesson): Response
    {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lesson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lesson_delete", methods={"POST"})
     */
    public function delete(Request $request, Lesson $lesson, DaysRepository $daysRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lesson->getId(), $request->request->get('_token'))) {
            $days = new Days();
            foreach($daysRepository->findAll() as $day){
                if($day->getLesson()!=null) {
                    if ($day->getLesson()->getId() == $lesson->getId()) {
                        $day->setLesson(null);
                        $days = $day;
                    }
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            if($days->getName() !=null){
                $entityManager->persist($days);
            }
            $entityManager->remove($lesson);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lesson_index', [], Response::HTTP_SEE_OTHER);
    }
}
