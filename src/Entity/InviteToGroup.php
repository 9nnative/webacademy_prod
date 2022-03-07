<?php

namespace App\Entity;

use App\Repository\InviteToGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InviteToGroupRepository::class)
 */
class InviteToGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="inviteToGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=UserGroup::class, cascade={"persist", "remove"}, inversedBy="inviteToGroups",cascade={"persist"})
     */
    private $wgroup;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="inviteToGroups")
     */
    private $course;

    /**
     * @ORM\OneToOne(targetEntity=Notification::class, mappedBy="inviteToGroup", cascade={"persist", "remove"})
     */
    private $notification;

    /**
     * @ORM\OneToOne(targetEntity=CourseNaviguation::class, inversedBy="inviteToGroup", cascade={"persist", "remove"})
     */
    private $navigation;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getWgroup(): ?UserGroup
    {
        return $this->wgroup;
    }

    public function setWgroup(?UserGroup $wgroup): self
    {
        $this->wgroup = $wgroup;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): self
    {
        // unset the owning side of the relation if necessary
        if ($notification === null && $this->notification !== null) {
            $this->notification->setInviteToGroup(null);
        }

        // set the owning side of the relation if necessary
        if ($notification !== null && $notification->getInviteToGroup() !== $this) {
            $notification->setInviteToGroup($this);
        }

        $this->notification = $notification;

        return $this;
    }

    public function getNavigation(): ?CourseNaviguation
    {
        return $this->navigation;
    }

    public function setNavigation(?CourseNaviguation $navigation): self
    {
        $this->navigation = $navigation;

        return $this;
    }
}
