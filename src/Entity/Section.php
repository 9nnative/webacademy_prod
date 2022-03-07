<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 */
class Section
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
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=Chapter::class, mappedBy="section")
     */
    private $chapter;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="sections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity=Links::class, mappedBy="section",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $links;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity=CourseNaviguation::class, mappedBy="section")
     */
    private $courseNaviguations;

    /**
     * @ORM\OneToMany(targetEntity=CourseFiles::class, mappedBy="section")
     */
    private $courseFiles;

    /**
     * @ORM\OneToOne(targetEntity=Course::class, mappedBy="autosaved_section", cascade={"persist", "remove"})
     */
    private $courseautosaved;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_hidden;

    public function __construct()
    {
        $this->chapter = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->courseNaviguations = new ArrayCollection();
        $this->courseFiles = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getChapter(): Collection
    {
        return $this->chapter;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapter->contains($chapter)) {
            $this->chapter[] = $chapter;
            $chapter->setSection($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapter->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getSection() === $this) {
                $chapter->setSection(null);
            }
        }

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
            $link->setSection($this);
        }

        return $this;
    }

    public function removeLink(Links $link): self
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getSection() === $this) {
                $link->setSection(null);
            }
        }

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

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
            $courseNaviguation->setSection($this);
        }

        return $this;
    }

    public function removeCourseNaviguation(CourseNaviguation $courseNaviguation): self
    {
        if ($this->courseNaviguations->removeElement($courseNaviguation)) {
            // set the owning side to null (unless already changed)
            if ($courseNaviguation->getSection() === $this) {
                $courseNaviguation->setSection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CourseFiles[]
     */
    public function getCourseFiles(): Collection
    {
        return $this->courseFiles;
    }

    public function addCourseFile(CourseFiles $courseFile): self
    {
        if (!$this->courseFiles->contains($courseFile)) {
            $this->courseFiles[] = $courseFile;
            $courseFile->setSection($this);
        }

        return $this;
    }

    public function removeCourseFile(CourseFiles $courseFile): self
    {
        if ($this->courseFiles->removeElement($courseFile)) {
            // set the owning side to null (unless already changed)
            if ($courseFile->getSection() === $this) {
                $courseFile->setSection(null);
            }
        }

        return $this;
    }

    public function getCourseautosaved(): ?Course
    {
        return $this->courseautosaved;
    }

    public function setCourseautosaved(?Course $courseautosaved): self
    {
        // unset the owning side of the relation if necessary
        if ($courseautosaved === null && $this->courseautosaved !== null) {
            $this->courseautosaved->setAutosavedSection(null);
        }

        // set the owning side of the relation if necessary
        if ($courseautosaved !== null && $courseautosaved->getAutosavedSection() !== $this) {
            $courseautosaved->setAutosavedSection($this);
        }

        $this->courseautosaved = $courseautosaved;

        return $this;
    }

    public function getIsHidden(): ?bool
    {
        return $this->is_hidden;
    }

    public function setIsHidden(?bool $is_hidden): self
    {
        $this->is_hidden = $is_hidden;

        return $this;
    }
}
