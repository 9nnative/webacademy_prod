<?php

namespace App\Entity;

use App\Repository\LinksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LinksRepository::class)
 */
class Links
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=UserGroup::class, inversedBy="links")
     */
    private $wgroup;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="links")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="links", cascade={"persist"})
     */
    private $section;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isavideo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $state;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getIsavideo(): ?bool
    {
        return $this->isavideo;
    }

    public function setIsavideo(?bool $isavideo): self
    {
        $this->isavideo = $isavideo;

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
}
