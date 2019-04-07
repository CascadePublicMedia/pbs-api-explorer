<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\FranchiseRepository")
 */
class Franchise
{
    /**
     * The Media Manage API endpoint for this entity.
     */
    public const ENDPOINT = 'franchises';

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $nola;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleSortable;

    /**
     * @ORM\ManyToOne(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Genre", inversedBy="franchises")
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=90, nullable=true)
     */
    private $descriptionShort;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionLong;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $premiered;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dfpExclude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $funderMessage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gaTrackingPage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gaTrackingEvent;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hashtag;

    /**
     * @ORM\ManyToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Platform", inversedBy="franchises", cascade={"persist", "merge"})
     */
    private $platforms;

    /**
     * @ORM\OneToMany(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Show", mappedBy="franchise", cascade={"persist", "merge", "remove"})
     */
    private $shows;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $links = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $images = [];

    public function __construct()
    {
        $this->platforms = new ArrayCollection();
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

    public function getNola(): ?string
    {
        return $this->nola;
    }

    public function setNola(?string $nola): self
    {
        $this->nola = $nola;

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

    public function getTitleSortable(): ?string
    {
        return $this->titleSortable;
    }

    public function setTitleSortable(string $titleSortable): self
    {
        $this->titleSortable = $titleSortable;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDescriptionShort(): ?string
    {
        return $this->descriptionShort;
    }

    public function setDescriptionShort(?string $descriptionShort): self
    {
        $this->descriptionShort = $descriptionShort;

        return $this;
    }

    public function getDescriptionLong(): ?string
    {
        return $this->descriptionLong;
    }

    public function setDescriptionLong(?string $descriptionLong): self
    {
        $this->descriptionLong = $descriptionLong;

        return $this;
    }

    public function getPremiered(): ?\DateTimeInterface
    {
        return $this->premiered;
    }

    public function setPremiered(?\DateTimeInterface $premiered): self
    {
        $this->premiered = $premiered;

        return $this;
    }

    public function getDfpExclude(): ?bool
    {
        return $this->dfpExclude;
    }

    public function setDfpExclude(bool $dfpExclude): self
    {
        $this->dfpExclude = $dfpExclude;

        return $this;
    }

    public function getFunderMessage(): ?string
    {
        return $this->funderMessage;
    }

    public function setFunderMessage(?string $funderMessage): self
    {
        $this->funderMessage = $funderMessage;

        return $this;
    }

    public function getGaTrackingPage(): ?string
    {
        return $this->gaTrackingPage;
    }

    public function setGaTrackingPage(?string $gaTrackingPage): self
    {
        $this->gaTrackingPage = $gaTrackingPage;

        return $this;
    }

    public function getGaTrackingEvent(): ?string
    {
        return $this->gaTrackingEvent;
    }

    public function setGaTrackingEvent(?string $gaTrackingEvent): self
    {
        $this->gaTrackingEvent = $gaTrackingEvent;

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

    public function getHashtag(): ?string
    {
        return $this->hashtag;
    }

    public function setHashtag(?string $hashtag): self
    {
        $this->hashtag = $hashtag;

        return $this;
    }

    /**
     * @return Collection|Platform[]
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platforms->contains($platform)) {
            $this->platforms->removeElement($platform);
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
            $show->setFranchise($this);
        }

        return $this;
    }

    public function removeShow(Show $show): self
    {
        if ($this->shows->contains($show)) {
            $this->shows->removeElement($show);
            // set the owning side to null (unless already changed)
            if ($show->getFranchise() === $this) {
                $show->setFranchise(null);
            }
        }

        return $this;
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function setLinks(?array $links): self
    {
        $this->links = $links;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }
}
