<?php

namespace App\Entity;

use App\Repository\CourseNaviguationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseNaviguationRepository::class)
 */
class CourseNaviguation
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
    private $step;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="courseNaviguations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="courseNaviguations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $started;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="courseNaviguations")
     */
    private $section;

    /**
     * @ORM\OneToOne(targetEntity=InviteToGroup::class, mappedBy="navigation", cascade={"persist", "remove"})
     */
    private $inviteToGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStep(): ?string
    {
        return $this->step;
    }

    public function setStep(string $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStarted(): ?bool
    {
        return $this->started;
    }

    public function setStarted(?bool $started): self
    {
        $this->started = $started;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getInviteToGroup(): ?InviteToGroup
    {
        return $this->inviteToGroup;
    }

    public function setInviteToGroup(?InviteToGroup $inviteToGroup): self
    {
        // unset the owning side of the relation if necessary
        if ($inviteToGroup === null && $this->inviteToGroup !== null) {
            $this->inviteToGroup->setNavigation(null);
        }

        // set the owning side of the relation if necessary
        if ($inviteToGroup !== null && $inviteToGroup->getNavigation() !== $this) {
            $inviteToGroup->setNavigation($this);
        }

        $this->inviteToGroup = $inviteToGroup;

        return $this;
    }
}
