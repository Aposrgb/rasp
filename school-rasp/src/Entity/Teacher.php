<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher
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
     * @ORM\OneToMany(targetEntity=Days::class, mappedBy="teacher")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDays(): ArrayCollection
    {
        return $this->days;
    }

    public function setDays(?Days $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function addDay(Days $day): self
    {
        if (!$this->days->contains($day)) {
            $this->days[] = $day;
            $day->setTeacher($this);
        }

        return $this;
    }

    public function removeDay(Days $day): self
    {
        if ($this->days->removeElement($day)) {
            // set the owning side to null (unless already changed)
            if ($day->getTeacher() === $this) {
                $day->setTeacher(null);
            }
        }

        return $this;
    }
}
