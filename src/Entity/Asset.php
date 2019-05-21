<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(
 *     repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\AssetRepository"
 * )
 */
class Asset
{
    /**
     * The Media Manage API endpoint for this entity.
     */
    public const ENDPOINT = 'assets';

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
     * @ORM\Column(type="string", length=255)
     */
    private $titleSortable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=90, nullable=true)
     */
    private $descriptionShort;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionLong;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $premiered;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $encored;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $legacyTpMediaId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dfpExclude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contentRating;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $contentRatingDescriptor = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $canEmbedPlayer;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $language;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $funderMessage;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $playerCode;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasCaptions;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Episode",
     *     inversedBy="assets",
     *     cascade={"persist", "merge"}
     * )
     */
    private $episode;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Season",
     *     inversedBy="assets",
     *     cascade={"persist", "merge"}
     * )
     */
    private $season;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Show",
     *     inversedBy="assets",
     *     cascade={"persist", "merge"}
     * )
     */
    private $show;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Franchise",
     *     inversedBy="assets",
     *     cascade={"persist", "merge"}
     * )
     */
    private $franchise;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $chapters = [];

    /**
     * @ORM\ManyToMany(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Platform"
     * )
     */
    private $platforms;

    /**
     * @ORM\OneToMany(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\AssetAvailability",
     *     mappedBy="asset",
     *     orphanRemoval=true,
     *     cascade={"persist", "merge"}
     * )
     */
    private $availabilities;

    /**
     * @ORM\OneToMany(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Image",
     *     mappedBy="asset",
     *     cascade={"persist", "merge"}
     * )
     */
    private $images;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $links = [];

    /**
     * @ORM\ManyToOne(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\GeoAvailabilityProfile")
     */
    private $geoProfile;

    /**
     * @ORM\ManyToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\GeoAvailabilityCountry")
     */
    private $countries;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $captions = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $videos = [];

    /**
     * @ORM\ManyToMany(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\AssetTag",
     *     inversedBy="assets",
     *     cascade={"persist", "merge"}
     * )
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\RemoteAsset")
     */
    private $relatedPromos;

    /**
     * @ORM\ManyToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Topic", inversedBy="assets")
     */
    private $topics;

    public function __construct()
    {
        $this->platforms = new ArrayCollection();
        $this->availabilities = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->relatedPromos = new ArrayCollection();
        $this->topics = new ArrayCollection();
    }

    public function __toString()
    {
        return "{$this->title} ({$this->type})";
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

    public function getTitleSortable(): ?string
    {
        return $this->titleSortable;
    }

    public function setTitleSortable(string $titleSortable): self
    {
        $this->titleSortable = $titleSortable;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getEncored(): ?\DateTimeInterface
    {
        return $this->encored;
    }

    public function setEncored(?\DateTimeInterface $encored): self
    {
        $this->encored = $encored;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLegacyTpMediaId(): ?string
    {
        return $this->legacyTpMediaId;
    }

    public function setLegacyTpMediaId(?string $legacyTpMediaId): self
    {
        $this->legacyTpMediaId = $legacyTpMediaId;

        return $this;
    }

    public function getDfpExclude(): ?bool
    {
        return $this->dfpExclude;
    }

    public function setDfpExclude(?bool $dfpExclude): self
    {
        $this->dfpExclude = $dfpExclude;

        return $this;
    }

    public function getContentRating(): ?string
    {
        return $this->contentRating;
    }

    public function setContentRating(?string $contentRating): self
    {
        $this->contentRating = $contentRating;

        return $this;
    }

    public function getContentRatingDescriptor(): ?array
    {
        return $this->contentRatingDescriptor;
    }

    public function setContentRatingDescriptor(?array $contentRatingDescriptor): self
    {
        $this->contentRatingDescriptor = $contentRatingDescriptor;

        return $this;
    }

    public function getCanEmbedPlayer(): ?bool
    {
        return $this->canEmbedPlayer;
    }

    public function setCanEmbedPlayer(?bool $canEmbedPlayer): self
    {
        $this->canEmbedPlayer = $canEmbedPlayer;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

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

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getPlayerCode(): ?string
    {
        return $this->playerCode;
    }

    public function setPlayerCode(?string $playerCode): self
    {
        $this->playerCode = $playerCode;

        return $this;
    }

    public function getHasCaptions(): ?bool
    {
        return $this->hasCaptions;
    }

    public function setHasCaptions(?bool $hasCaptions): self
    {
        $this->hasCaptions = $hasCaptions;

        return $this;
    }

    public function getEpisode(): ?Episode
    {
        return $this->episode;
    }

    public function setEpisode(?Episode $episode): self
    {
        $this->episode = $episode;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getShow(): ?Show
    {
        return $this->show;
    }

    public function setShow(?Show $show): self
    {
        $this->show = $show;

        return $this;
    }

    public function getFranchise(): ?Franchise
    {
        return $this->franchise;
    }

    public function setFranchise(?Franchise $franchise): self
    {
        $this->franchise = $franchise;

        return $this;
    }

    public function getParent()
    {
        foreach (['franchise', 'show', 'season', 'episode'] as $type) {
            if ($this->{$type}) {
                return $this->{$type};
            }
        }

        return NULL;
    }

    public function getChapters(): ?array
    {
        return $this->chapters;
    }

    public function setChapters(?array $chapters): self
    {
        $this->chapters = $chapters;

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
     * @return Collection|AssetAvailability[]
     */
    public function getAvailabilities(): Collection
    {
        return $this->availabilities;
    }

    public function addAvailability(AssetAvailability $availability): self
    {
        if (!$this->availabilities->contains($availability)) {
            $this->availabilities[] = $availability;
            $availability->setAsset($this);
        }

        return $this;
    }

    public function removeAvailability(AssetAvailability $availability): self
    {
        if ($this->availabilities->contains($availability)) {
            $this->availabilities->removeElement($availability);
            // set the owning side to null (unless already changed)
            if ($availability->getAsset() === $this) {
                $availability->setAsset(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAsset($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAsset() === $this) {
                $image->setAsset(null);
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

    public function getGeoProfile(): ?GeoAvailabilityProfile
    {
        return $this->geoProfile;
    }

    public function setGeoProfile(?GeoAvailabilityProfile $geoProfile): self
    {
        $this->geoProfile = $geoProfile;

        return $this;
    }

    /**
     * @return Collection|GeoAvailabilityCountry[]
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(GeoAvailabilityCountry $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries[] = $country;
        }

        return $this;
    }

    public function removeCountry(GeoAvailabilityCountry $country): self
    {
        if ($this->countries->contains($country)) {
            $this->countries->removeElement($country);
        }

        return $this;
    }

    public function getCaptions(): ?array
    {
        return $this->captions;
    }

    public function setCaptions(?array $captions): self
    {
        $this->captions = $captions;

        return $this;
    }

    public function getVideos(): ?array
    {
        return $this->videos;
    }

    public function setVideos(?array $videos): self
    {
        $this->videos = $videos;

        return $this;
    }

    /**
     * @return Collection|AssetTag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(AssetTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(AssetTag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|RemoteAsset[]
     */
    public function getRelatedPromos(): Collection
    {
        return $this->relatedPromos;
    }

    public function addRelatedPromo(RemoteAsset $relatedPromo): self
    {
        if (!$this->relatedPromos->contains($relatedPromo)) {
            $this->relatedPromos[] = $relatedPromo;
        }

        return $this;
    }

    public function removeRelatedPromo(RemoteAsset $relatedPromo): self
    {
        if ($this->relatedPromos->contains($relatedPromo)) {
            $this->relatedPromos->removeElement($relatedPromo);
        }

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
        }

        return $this;
    }
}
