<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 */
class UserGroup
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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="userGroups")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="usercreatedGroups",cascade={"persist"})
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="wgroup", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Links::class, mappedBy="wgroup")
     */
    private $links;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, inversedBy="userGroups")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="wgroup", orphanRemoval=true)
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, mappedBy="wgroup", orphanRemoval=true)
     */
    private $linkedCourses;

    /**
     * @ORM\OneToMany(targetEntity=InviteToGroup::class, mappedBy="wgroup", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $inviteToGroups;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="wgroup", orphanRemoval=true)
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity=GroupPrompt::class, mappedBy="wgroup", orphanRemoval=true)
     */
    private $groupPrompts;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->created_by = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->linkedCourses = new ArrayCollection();
        $this->inviteToGroups = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->groupPrompts = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }


    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setWgroup($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getWgroup() === $this) {
                $message->setWgroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Links[]
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Links $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setWgroup($this);
        }

        return $this;
    }

    public function removeLink(Links $link): self
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getWgroup() === $this) {
                $link->setWgroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        $this->courses->removeElement($course);

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setWgroup($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getWgroup() === $this) {
                $event->setWgroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getLinkedCourses(): Collection
    {
        return $this->linkedCourses;
    }

    public function addLinkedCourse(Course $linkedCourse): self
    {
        if (!$this->linkedCourses->contains($linkedCourse)) {
            $this->linkedCourses[] = $linkedCourse;
            $linkedCourse->addWgroup($this);
        }

        return $this;
    }

    public function removeLinkedCourse(Course $linkedCourse): self
    {
        if ($this->linkedCourses->removeElement($linkedCourse)) {
            $linkedCourse->removeWgroup($this);
        }

        return $this;
    }

    /**
     * @return Collection|InviteToGroup[]
     */
    public function getInviteToGroups(): Collection
    {
        return $this->inviteToGroups;
    }

    public function addInviteToGroup(InviteToGroup $inviteToGroup): self
    {
        if (!$this->inviteToGroups->contains($inviteToGroup)) {
            $this->inviteToGroups[] = $inviteToGroup;
            $inviteToGroup->setWgroup($this);
        }

        return $this;
    }

    public function removeInviteToGroup(InviteToGroup $inviteToGroup): self
    {
        if ($this->inviteToGroups->removeElement($inviteToGroup)) {
            // set the owning side to null (unless already changed)
            if ($inviteToGroup->getWgroup() === $this) {
                $inviteToGroup->setWgroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setWgroup($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getWgroup() === $this) {
                $notification->setWgroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupPrompt[]
     */
    public function getGroupPrompts(): Collection
    {
        return $this->groupPrompts;
    }

    public function addGroupPrompt(GroupPrompt $groupPrompt): self
    {
        if (!$this->groupPrompts->contains($groupPrompt)) {
            $this->groupPrompts[] = $groupPrompt;
            $groupPrompt->setWgroup($this);
        }

        return $this;
    }

    public function removeGroupPrompt(GroupPrompt $groupPrompt): self
    {
        if ($this->groupPrompts->removeElement($groupPrompt)) {
            // set the owning side to null (unless already changed)
            if ($groupPrompt->getWgroup() === $this) {
                $groupPrompt->setWgroup(null);
            }
        }

        return $this;
    }

}
