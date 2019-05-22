<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\ListingRepository")
 */
class Listing
{
    /**
     * The human readable name for this class.
     */
    public const NAME = 'listing';

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $packageId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $taped;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $durationMinutes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nolaEpisode;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $nolaRoot;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $seasonPremiereFinale;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $specialWarnings;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $startTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $animated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $closedCaptions;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hd;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stereo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $showId;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram",
     *     inversedBy="listings"
     * )
     */
    private $program;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $episodeTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $episodeDescription;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Feed",
     *     inversedBy="listings"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $feed;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function __toString()
    {
        if (!empty($this->episodeTitle)) {
            $title = $this->episodeTitle . '(' . $this->title . ')';
        }
        else {
            $title = $this->title;
        }
        return $title;
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

    public function getPackageId(): ?string
    {
        return $this->packageId;
    }

    public function setPackageId(?string $packageId): self
    {
        $this->packageId = $packageId;

        return $this;
    }

    public function getTaped(): ?bool
    {
        return $this->taped;
    }

    public function setTaped(bool $taped): self
    {
        $this->taped = $taped;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDurationMinutes(): ?int
    {
        return $this->durationMinutes;
    }

    public function setDurationMinutes(int $durationMinutes): self
    {
        $this->durationMinutes = $durationMinutes;

        return $this;
    }

    public function getNolaEpisode(): ?string
    {
        return $this->nolaEpisode;
    }

    public function setNolaEpisode(?string $nolaEpisode): self
    {
        $this->nolaEpisode = $nolaEpisode;

        return $this;
    }

    public function getNolaRoot(): ?string
    {
        return $this->nolaRoot;
    }

    public function setNolaRoot(?string $nolaRoot): self
    {
        $this->nolaRoot = $nolaRoot;

        return $this;
    }

    public function getSeasonPremiereFinale(): ?string
    {
        return $this->seasonPremiereFinale;
    }

    public function setSeasonPremiereFinale(?string $seasonPremiereFinale): self
    {
        $this->seasonPremiereFinale = $seasonPremiereFinale;

        return $this;
    }

    public function getSpecialWarnings(): ?string
    {
        return $this->specialWarnings;
    }

    public function setSpecialWarnings(?string $specialWarnings): self
    {
        $this->specialWarnings = $specialWarnings;

        return $this;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAnimated(): ?bool
    {
        return $this->animated;
    }

    public function setAnimated(bool $animated): self
    {
        $this->animated = $animated;

        return $this;
    }

    public function getClosedCaptions(): ?bool
    {
        return $this->closedCaptions;
    }

    public function setClosedCaptions(bool $closedCaptions): self
    {
        $this->closedCaptions = $closedCaptions;

        return $this;
    }

    public function getHd(): ?bool
    {
        return $this->hd;
    }

    public function setHd(bool $hd): self
    {
        $this->hd = $hd;

        return $this;
    }

    public function getStereo(): ?bool
    {
        return $this->stereo;
    }

    public function setStereo(bool $stereo): self
    {
        $this->stereo = $stereo;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getShowId(): ?string
    {
        return $this->showId;
    }

    public function setShowId(?string $showId): self
    {
        $this->showId = $showId;

        return $this;
    }

    public function getProgram(): ?ScheduleProgram
    {
        return $this->program;
    }

    public function setProgram(?ScheduleProgram $program): self
    {
        $this->program = $program;

        return $this;
    }

    public function getEpisodeTitle(): ?string
    {
        return $this->episodeTitle;
    }

    public function setEpisodeTitle(?string $episodeTitle): self
    {
        $this->episodeTitle = $episodeTitle;

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

    public function getEpisodeDescription(): ?string
    {
        return $this->episodeDescription;
    }

    public function setEpisodeDescription(?string $episodeDescription): self
    {
        $this->episodeDescription = $episodeDescription;

        return $this;
    }

    public function getFeed(): ?Feed
    {
        return $this->feed;
    }

    public function setFeed(?Feed $feed): self
    {
        $this->feed = $feed;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
