<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $forename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="created_by", orphanRemoval=true)
     */
    private $courses;

    /**
     * @ORM\ManyToMany(targetEntity=UserGroup::class, mappedBy="users")
     */
    private $userGroups;

    /**
     * @ORM\OneToMany(targetEntity=UserGroup::class, mappedBy="created_by", orphanRemoval=true)
     */
    private $createdUserGroups;

    /**
     * @ORM\OneToMany(targetEntity=UserGroup::class, mappedBy="created_by", orphanRemoval=true)
     */
    private $usercreatedGroups;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brochure_filename;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="wroteby", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="actionby", orphanRemoval=true)
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity=Links::class, mappedBy="user", orphanRemoval=true)
     */
    private $links;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $inscription_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lasttimeseen;

    /**
     * @ORM\OneToMany(targetEntity=InviteToGroup::class, mappedBy="user", orphanRemoval=true)
     */
    private $inviteToGroups;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $notifstate;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, inversedBy="users")
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity=CourseNaviguation::class, mappedBy="user", orphanRemoval=true)
     */
    private $courseNaviguations;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $darkmode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\OneToMany(targetEntity=Access::class, mappedBy="user", orphanRemoval=true)
     */
    private $accesses;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tutorial;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $cheatcode;

    /**
     * @ORM\ManyToMany(targetEntity=Notification::class, mappedBy="users")
     */
    private $notifs;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="created_by")
     */
    private $tickets;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
        $this->createdUserGroups = new ArrayCollection();
        $this->usercreatedGroups = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->inviteToGroups = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->courseNaviguations = new ArrayCollection();
        $this->accesses = new ArrayCollection();
        $this->notifs = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getForename(): ?string
    {
        return $this->forename;
    }

    public function setForename(?string $forename): self
    {
        $this->forename = $forename;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

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
            $course->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getCreatedBy() === $this) {
                $course->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
            $userGroup->addUser($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->removeElement($userGroup)) {
            $userGroup->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getUsercreatedGroups(): Collection
    {
        return $this->usercreatedGroups;
    }

    public function addUsercreatedGroup(UserGroup $usercreatedGroup): self
    {
        if (!$this->usercreatedGroups->contains($usercreatedGroup)) {
            $this->usercreatedGroups[] = $usercreatedGroup;
            $usercreatedGroup->setCreatedBy($this);
        }

        return $this;
    }

    public function removeUsercreatedGroup(UserGroup $usercreatedGroup): self
    {
        if ($this->usercreatedGroups->removeElement($usercreatedGroup)) {
            // set the owning side to null (unless already changed)
            if ($usercreatedGroup->getCreatedBy() === $this) {
                $usercreatedGroup->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getBrochureFilename(): ?string
    {
        return $this->brochure_filename;
    }

    public function setBrochureFilename(?string $brochure_filename): self
    {
        $this->brochure_filename = $brochure_filename;

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
            $message->setWroteby($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getWroteby() === $this) {
                $message->setWroteby(null);
            }
        }

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
            $event->setActionby($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getActionby() === $this) {
                $event->setActionby(null);
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
            $link->setUser($this);
        }

        return $this;
    }

    public function removeLink(Links $link): self
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getUser() === $this) {
                $link->setUser(null);
            }
        }

        return $this;
    }

    public function getInscriptionDate(): ?\DateTimeInterface
    {
        return $this->inscription_date;
    }

    public function setInscriptionDate(\DateTimeInterface $inscription_date): self
    {
        $this->inscription_date = $inscription_date;

        return $this;
    }

    public function getLasttimeseen(): ?\DateTimeInterface
    {
        return $this->lasttimeseen;
    }

    public function setLasttimeseen(?\DateTimeInterface $lasttimeseen): self
    {
        $this->lasttimeseen = $lasttimeseen;

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
            $inviteToGroup->setUser($this);
        }

        return $this;
    }

    public function removeInviteToGroup(InviteToGroup $inviteToGroup): self
    {
        if ($this->inviteToGroups->removeElement($inviteToGroup)) {
            // set the owning side to null (unless already changed)
            if ($inviteToGroup->getUser() === $this) {
                $inviteToGroup->setUser(null);
            }
        }

        return $this;
    }

    public function getNotifstate(): ?bool
    {
        return $this->notifstate;
    }

    public function setNotifstate(?bool $notifstate): self
    {
        $this->notifstate = $notifstate;

        return $this;
    }


    /**
     * @return Collection|Course[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Course $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(Course $favorite): self
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    /**
     * @return Collection|CourseNaviguation[]
     */
    public function getCourseNaviguations(): Collection
    {
        return $this->courseNaviguations;
    }

    public function addCourseNaviguation(CourseNaviguation $courseNaviguation): self
    {
        if (!$this->courseNaviguations->contains($courseNaviguation)) {
            $this->courseNaviguations[] = $courseNaviguation;
            $courseNaviguation->setUser($this);
        }

        return $this;
    }

    public function removeCourseNaviguation(CourseNaviguation $courseNaviguation): self
    {
        if ($this->courseNaviguations->removeElement($courseNaviguation)) {
            // set the owning side to null (unless already changed)
            if ($courseNaviguation->getUser() === $this) {
                $courseNaviguation->setUser(null);
            }
        }

        return $this;
    }

    public function getDarkmode(): ?bool
    {
        return $this->darkmode;
    }

    public function setDarkmode(?bool $darkmode): self
    {
        $this->darkmode = $darkmode;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * @return Collection|Access[]
     */
    public function getAccesses(): Collection
    {
        return $this->accesses;
    }

    public function addAccess(Access $access): self
    {
        if (!$this->accesses->contains($access)) {
            $this->accesses[] = $access;
            $access->setUser($this);
        }

        return $this;
    }

    public function removeAccess(Access $access): self
    {
        if ($this->accesses->removeElement($access)) {
            // set the owning side to null (unless already changed)
            if ($access->getUser() === $this) {
                $access->setUser(null);
            }
        }

        return $this;
    }

    public function getTutorial(): ?bool
    {
        return $this->tutorial;
    }

    public function setTutorial(?bool $tutorial): self
    {
        $this->tutorial = $tutorial;

        return $this;
    }

    public function getCheatcode(): ?string
    {
        return $this->cheatcode;
    }

    public function setCheatcode(?string $cheatcode): self
    {
        $this->cheatcode = $cheatcode;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifs(): Collection
    {
        return $this->notifs;
    }

    public function addNotif(Notification $notif): self
    {
        if (!$this->notifs->contains($notif)) {
            $this->notifs[] = $notif;
            $notif->addUser($this);
        }

        return $this;
    }

    public function removeNotif(Notification $notif): self
    {
        if ($this->notifs->removeElement($notif)) {
            $notif->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setCreatedBy($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getCreatedBy() === $this) {
                $ticket->setCreatedBy(null);
            }
        }

        return $this;
    }

}
