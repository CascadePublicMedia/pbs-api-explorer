<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\GenreRepository")
 */
class Genre
{
    /**
     * The Media Manage API endpoint for this entity.
     */
    public const ENDPOINT = 'genres';

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $created;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Franchise", mappedBy="genre")
     */
    private $franchises;

    /**
     * @ORM\OneToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Show", mappedBy="genre")
     */
    private $shows;

    public function __construct()
    {
        $this->franchises = new ArrayCollection();
        $this->shows = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?string
    {
      return $this->id;
    }

    public function setId(string $id): self
    {
      $this->id = $id;

      return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(\DateTimeImmutable $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection|Franchise[]
     */
    public function getFranchises(): Collection
    {
        return $this->franchises;
    }

    public function addFranchise(Franchise $franchise): self
    {
        if (!$this->franchises->contains($franchise)) {
            $this->franchises[] = $franchise;
            $franchise->setGenre($this);
        }

        return $this;
    }

    public function removeFranchise(Franchise $franchise): self
    {
        if ($this->franchises->contains($franchise)) {
            $this->franchises->removeElement($franchise);
            // set the owning side to null (unless already changed)
            if ($franchise->getGenre() === $this) {
                $franchise->setGenre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Show[]
     */
    public function getShows(): Collection
    {
        return $this->shows;
    }

    public function addShow(Show $show): self
    {
        if (!$this->shows->contains($show)) {
            $this->shows[] = $show;
            $show->setGenre($this);
        }

        return $this;
    }

    public function removeShow(Show $show): self
    {
        if ($this->shows->contains($show)) {
            $this->shows->removeElement($show);
            // set the owning side to null (unless already changed)
            if ($show->getGenre() === $this) {
                $show->setGenre(null);
            }
        }

        return $this;
    }
}
