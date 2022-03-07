<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 */
class Chapter
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
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="chapters")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity=Subchapter::class, mappedBy="chapter")
     */
    private $subchapters;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="chapter")
     */
    private $section;

    public function __construct()
    {
        $this->subchapters = new ArrayCollection();
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
     * @return Collection|Subchapter[]
     */
    public function getSubchapters(): Collection
    {
        return $this->subchapters;
    }

    public function addSubchapter(Subchapter $subchapter): self
    {
        if (!$this->subchapters->contains($subchapter)) {
            $this->subchapters[] = $subchapter;
            $subchapter->setChapter($this);
        }

        return $this;
    }

    public function removeSubchapter(Subchapter $subchapter): self
    {
        if ($this->subchapters->removeElement($subchapter)) {
            // set the owning side to null (unless already changed)
            if ($subchapter->getChapter() === $this) {
                $subchapter->setChapter(null);
            }
        }

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


}
