<?php

namespace App\Entity;

use App\Repository\DaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DaysRepository::class)
 */
class Days
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Schedule::class, inversedBy="days" )
     */
    private $schedule;

    /**
     * @ORM\Column(type="integer", nullable="true")
     */
    private $timeLesson;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="days", cascade={"persist", "remove"})
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity=Lesson::class, inversedBy="days", cascade={"persist", "remove"})
     */
    private $lesson;


    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function __toString()
    {
        return $this->getName();
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getTimeLesson(): ?int
    {
        return $this->timeLesson;
    }

    public function setTimeLesson(int $timeLesson): self
    {
        $this->timeLesson = $timeLesson;

        return $this;
    }

    public function getTeacher()
    {
        return $this->teacher;
    }
    public function setTeacher($teacher)
    {
        $this->teacher=$teacher;
    }
    public function getLesson()
    {
        return $this->lesson;
    }
    public function setLesson($lesson)
    {
        $this->lesson=$lesson;
    }




}
