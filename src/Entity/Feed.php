<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\FeedRepository")
 */
class Feed
{
    /**
     * The human readable name for this class.
     */
    public const NAME = 'feed';

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timezone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $analogChannel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $digitalChannel;

    /**
     * @ORM\OneToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Listing", mappedBy="feed", orphanRemoval=true)
     */
    private $listings;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $externalId;

    public function __construct()
    {
        $this->listings = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->fullName;
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

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getAnalogChannel(): ?string
    {
        return $this->analogChannel;
    }

    public function setAnalogChannel(?string $analogChannel): self
    {
        $this->analogChannel = $analogChannel;

        return $this;
    }

    public function getDigitalChannel(): ?string
    {
        return $this->digitalChannel;
    }

    public function setDigitalChannel(?string $digitalChannel): self
    {
        $this->digitalChannel = $digitalChannel;

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
            $listing->setFeed($this);
        }

        return $this;
    }

    public function removeListing(Listing $listing): self
    {
        if ($this->listings->contains($listing)) {
            $this->listings->removeElement($listing);
            // set the owning side to null (unless already changed)
            if ($listing->getFeed() === $this) {
                $listing->setFeed(null);
            }
        }

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }
}
