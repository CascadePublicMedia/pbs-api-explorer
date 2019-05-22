<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\ScheduleProgramRepository")
 */
class ScheduleProgram
{
    /**
     * The TVSS API endpoint for this entity.
     */
    public const ENDPOINT = 'programs';

    /**
     * The human readable name for this class.
     */
    public const NAME = 'program';

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $externalId;

    /**
     * @ORM\Column(type="integer")
     */
    private $programId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rovi;

    /**
     * @ORM\OneToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Listing", mappedBy="program")
     */
    private $listings;

    public function __construct()
    {
        $this->listings = new ArrayCollection();
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getProgramId(): ?int
    {
        return $this->programId;
    }

    public function setProgramId(int $programId): self
    {
        $this->programId = $programId;

        return $this;
    }

    public function getRovi(): ?int
    {
        return $this->rovi;
    }

    public function setRovi(?int $rovi): self
    {
        $this->rovi = $rovi;

        return $this;
    }

    /**
     * @return Collection|Listing[]
     */
    public function getListings(): Collection
    {
        return $this->listings;
    }

    public function addListing(Listing $listing): self
    {
        if (!$this->listings->contains($listing)) {
            $this->listings[] = $listing;
            $listing->setProgram($this);
        }

        return $this;
    }

    public function removeListing(Listing $listing): self
    {
        if ($this->listings->contains($listing)) {
            $this->listings->removeElement($listing);
            // set the owning side to null (unless already changed)
            if ($listing->getProgram() === $this) {
                $listing->setProgram(null);
            }
        }

        return $this;
    }
}
