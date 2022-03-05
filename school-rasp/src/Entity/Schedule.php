<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScheduleRepository::class)
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, nullable="true")
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity=Days::class, mappedBy="schedule")
     */
    private $days;


    public function __construct()
    {
        $this->days = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function __toString()
    {
        return $this->getName();
    }
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Days[]
     */
    public function getDays(): Collection
    {
        return $this->days;
    }

    public function addDay(Days $day): self
    {
        if (!$this->days->contains($day)) {
            $this->days[] = $day;
            $day->setSchedule($this);
        }

        return $this;
    }

    public function removeDay(Days $day): self
    {
        if ($this->days->removeElement($day)) {
            // set the owning side to null (unless already changed)
            if ($day->getSchedule() === $this) {
                $day->setSchedule(null);
            }
        }

        return $this;
    }



}
