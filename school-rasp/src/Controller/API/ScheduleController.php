<?php


namespace App\Controller\API;

use App\Entity\Schedule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ScheduleController
 * @package App\Controller\API
 * @Route("/api/schedule")
 */
class ScheduleController extends AbstractController
{
    /**
     * @return JsonResponse
     * @Route("/{name}", methods={"GET"})
     */
    public function index(Schedule $schedule): JsonResponse
    {
        return $this->json($schedule);
    }

}