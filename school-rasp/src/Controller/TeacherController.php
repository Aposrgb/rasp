<?php

namespace App\Controller;

use App\Entity\Days;
use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Repository\DaysRepository;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teacher")
 */
class TeacherController extends AbstractController
{
    /**
     * @Route("/", name="teacher_index", methods={"GET"})
     */
    public function index(TeacherRepository $teacherRepository): Response
    {
        return $this->render('teacher/index.html.twig', [
            'teachers' => $teacherRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="teacher_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacher);
            $entityManager->flush();

            return $this->redirectToRoute('teacher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teacher/new.html.twig', [
            'teacher' => $teacher,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="teacher_show", methods={"GET"})
     */
    public function show(Teacher $teacher): Response
    {
        return $this->render('teacher/show.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="teacher_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Teacher $teacher): Response
    {
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('teacher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teacher/edit.html.twig', [
            'teacher' => $teacher,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="teacher_delete", methods={"POST"})
     */
    public function delete(Request $request, Teacher $teacher, DaysRepository $daysRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->request->get('_token'))) {
            $days = new Days();
            foreach($daysRepository->findAll() as $day){
                if($day->getTeacher()->getId() == $teacher->getId()){
                    $day->setTeacher(null);
                    $days = $day;
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            if($days->getName() !=null){
                $entityManager->persist($days);
            }
            $entityManager->remove($teacher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('teacher_index', [], Response::HTTP_SEE_OTHER);
    }
}
