<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
    private $title;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $state;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_paying;

    /**
     * @ORM\OneToMany(targetEntity=Chapter::class, mappedBy="course", cascade={"persist"})
     */
    private $chapters;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="course")
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity=UserGroup::class, mappedBy="courses")
     */
    private $userGroups;

    /**
     * @ORM\ManyToMany(targetEntity=UserGroup::class, inversedBy="linkedCourses")
     */
    private $wgroup;

    /**
     * @ORM\OneToMany(targetEntity=InviteToGroup::class, mappedBy="course", orphanRemoval=true)
     */
    private $inviteToGroups;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_certifying;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brochure_filename;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="courses")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $views;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="course", orphanRemoval=true)
     */
    private $sections;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favorites")
     */
    private $users;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $step;

    /**
     * @ORM\OneToMany(targetEntity=CourseNaviguation::class, mappedBy="course", orphanRemoval=true)
     */
    private $courseNaviguations;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $date_end_bool;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_end;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prerequis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $objectifs_pedago;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_start_str;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_end_str;

    /**
     * @ORM\OneToOne(targetEntity=Section::class, inversedBy="courseautosaved", cascade={"persist", "remove"})
     */
    private $autosaved_section;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $autosave;

    /**
     * @ORM\Column(type="array")
     */
    private $targets = [];

    /**
     * @ORM\ManyToMany(targetEntity=FeedEvent::class, mappedBy="course")
     */
    private $feedEvents;


    public function __construct()
    {
        $this->chapters = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
        $this->wgroup = new ArrayCollection();
        $this->userGroupx = new ArrayCollection();
        $this->inviteToGroups = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->courseNaviguations = new ArrayCollection();
        $this->feedEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getIsPaying(): ?bool
    {
        return $this->is_paying;
    }

    public function setIsPaying(bool $is_paying): self
    {
        $this->is_paying = $is_paying;

        return $this;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
            $chapter->setCourse($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getCourse() === $this) {
                $chapter->setCourse(null);
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
            $event->addCourse($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeCourse($this);
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
            $userGroup->addCourse($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->removeElement($userGroup)) {
            $userGroup->removeCourse($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getWgroup(): Collection
    {
        return $this->wgroup;
    }

    public function addWgroup(UserGroup $wgroup): self
    {
        if (!$this->wgroup->contains($wgroup)) {
            $this->wgroup[] = $wgroup;
        }

        return $this;
    }

    public function removeWgroup(UserGroup $wgroup): self
    {
        $this->wgroup->removeElement($wgroup);

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
            $inviteToGroup->setCourse($this);
        }

        return $this;
    }

    public function removeInviteToGroup(InviteToGroup $inviteToGroup): self
    {
        if ($this->inviteToGroups->removeElement($inviteToGroup)) {
            // set the owning side to null (unless already changed)
            if ($inviteToGroup->getCourse() === $this) {
                $inviteToGroup->setCourse(null);
            }
        }

        return $this;
    }

    public function getIsCertifying(): ?bool
    {
        return $this->is_certifying;
    }

    public function setIsCertifying(?bool $is_certifying): self
    {
        $this->is_certifying = $is_certifying;

        return $this;
    }

    public function getBrochureFilename(): ?string
    {
        return $this->brochure_filename;
    }

    public function setBrochureFilename(string $brochure_filename): self
    {
        $this->brochure_filename = $brochure_filename;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setCourse($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getCourse() === $this) {
                $section->setCourse(null);
            }
        }

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->is_published;
    }

    public function setIsPublished(bool $is_published): self
    {
        $this->is_published = $is_published;

        return $this;
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
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(?int $step): self
    {
        $this->step = $step;

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
            $courseNaviguation->setCourse($this);
        }

        return $this;
    }

    public function removeCourseNaviguation(CourseNaviguation $courseNaviguation): self
    {
        if ($this->courseNaviguations->removeElement($courseNaviguation)) {
            // set the owning side to null (unless already changed)
            if ($courseNaviguation->getCourse() === $this) {
                $courseNaviguation->setCourse(null);
            }
        }

        return $this;
    }


    public function getDateEndBool(): ?bool
    {
        return $this->date_end_bool;
    }

    public function setDateEndBool(bool $date_end_bool): self
    {
        $this->date_end_bool = $date_end_bool;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(?\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(?\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getPrerequis(): ?string
    {
        return $this->prerequis;
    }

    public function setPrerequis(?string $prerequis): self
    {
        $this->prerequis = $prerequis;

        return $this;
    }

    public function getObjectifsPedago(): ?string
    {
        return $this->objectifs_pedago;
    }

    public function setObjectifsPedago(?string $objectifs_pedago): self
    {
        $this->objectifs_pedago = $objectifs_pedago;

        return $this;
    }

    public function getDateStartStr(): ?string
    {
        return $this->date_start_str;
    }

    public function setDateStartStr(?string $date_start_str): self
    {
        $this->date_start_str = $date_start_str;

        return $this;
    }

    public function getDateEndStr(): ?string
    {
        return $this->date_end_str;
    }

    public function setDateEndStr(?string $date_end_str): self
    {
        $this->date_end_str = $date_end_str;

        return $this;
    }

    public function getAutosavedSection(): ?Section
    {
        return $this->autosaved_section;
    }

    public function setAutosavedSection(?Section $autosaved_section): self
    {
        $this->autosaved_section = $autosaved_section;

        return $this;
    }

    public function getAutosave(): ?bool
    {
        return $this->autosave;
    }

    public function setAutosave(?bool $autosave): self
    {
        $this->autosave = $autosave;

        return $this;
    }

    public function getTargets(): ?array
    {
        return $this->targets;
    }

    public function setTargets(array $targets): self
    {
        $this->targets = $targets;

        return $this;
    }

    /**
     * @return Collection<int, FeedEvent>
     */
    public function getFeedEvents(): Collection
    {
        return $this->feedEvents;
    }

    public function addFeedEvent(FeedEvent $feedEvent): self
    {
        if (!$this->feedEvents->contains($feedEvent)) {
            $this->feedEvents[] = $feedEvent;
            $feedEvent->addCourse($this);
        }

        return $this;
    }

    public function removeFeedEvent(FeedEvent $feedEvent): self
    {
        if ($this->feedEvents->removeElement($feedEvent)) {
            $feedEvent->removeCourse($this);
        }

        return $this;
    }


}
