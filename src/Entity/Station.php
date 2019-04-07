<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\StationRepository")
 */
class Station
{
    /**
     * The Station Manage API endpoint for this entity.
     */
    public const ENDPOINT = 'stations';

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $callSign;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullCommonName;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $shortCommonName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tvssUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $donateUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timezone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $timezoneSecondary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoPortalUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoPortalBannerUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $websiteUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitterUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kidsStationUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passportUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressState;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressZipCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressCountryCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tagLine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackingCodePage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackingCodeEvent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $primaryChannel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $primetimeStart;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $images = [];

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pdp;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $passportEnabled;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $annualPassportQualifyingAmount;

    /**
     * @ORM\OneToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Audience", mappedBy="station")
     */
    private $audiences;

    public function __construct()
    {
        $this->audiences = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->fullCommonName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCallSign(): ?string
    {
        return $this->callSign;
    }

    public function setCallSign(string $callSign): self
    {
        $this->callSign = $callSign;

        return $this;
    }

    public function getFullCommonName(): ?string
    {
        return $this->fullCommonName;
    }

    public function setFullCommonName(string $fullCommonName): self
    {
        $this->fullCommonName = $fullCommonName;

        return $this;
    }

    public function getShortCommonName(): ?string
    {
        return $this->shortCommonName;
    }

    public function setShortCommonName(string $shortCommonName): self
    {
        $this->shortCommonName = $shortCommonName;

        return $this;
    }

    public function getTvssUrl(): ?string
    {
        return $this->tvssUrl;
    }

    public function setTvssUrl(string $tvssUrl): self
    {
        $this->tvssUrl = $tvssUrl;

        return $this;
    }

    public function getDonateUrl(): ?string
    {
        return $this->donateUrl;
    }

    public function setDonateUrl(string $donateUrl): self
    {
        $this->donateUrl = $donateUrl;

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

    public function getTimezoneSecondary(): ?string
    {
        return $this->timezoneSecondary;
    }

    public function setTimezoneSecondary(?string $timezoneSecondary): self
    {
        $this->timezoneSecondary = $timezoneSecondary;

        return $this;
    }

    public function getVideoPortalUrl(): ?string
    {
        return $this->videoPortalUrl;
    }

    public function setVideoPortalUrl(?string $videoPortalUrl): self
    {
        $this->videoPortalUrl = $videoPortalUrl;

        return $this;
    }

    public function getVideoPortalBannerUrl(): ?string
    {
        return $this->videoPortalBannerUrl;
    }

    public function setVideoPortalBannerUrl(?string $videoPortalBannerUrl): self
    {
        $this->videoPortalBannerUrl = $videoPortalBannerUrl;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getFacebookUrl(): ?string
    {
        return $this->facebookUrl;
    }

    public function setFacebookUrl(?string $facebookUrl): self
    {
        $this->facebookUrl = $facebookUrl;

        return $this;
    }

    public function getTwitterUrl(): ?string
    {
        return $this->twitterUrl;
    }

    public function setTwitterUrl(?string $twitterUrl): self
    {
        $this->twitterUrl = $twitterUrl;

        return $this;
    }

    public function getKidsStationUrl(): ?string
    {
        return $this->kidsStationUrl;
    }

    public function setKidsStationUrl(?string $kidsStationUrl): self
    {
        $this->kidsStationUrl = $kidsStationUrl;

        return $this;
    }

    public function getPassportUrl(): ?string
    {
        return $this->passportUrl;
    }

    public function setPassportUrl(?string $passportUrl): self
    {
        $this->passportUrl = $passportUrl;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->addressCity;
    }

    public function setAddressCity(?string $addressCity): self
    {
        $this->addressCity = $addressCity;

        return $this;
    }

    public function getAddressState(): ?string
    {
        return $this->addressState;
    }

    public function setAddressState(?string $addressState): self
    {
        $this->addressState = $addressState;

        return $this;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(?string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(?string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function getAddressZipCode(): ?string
    {
        return $this->addressZipCode;
    }

    public function setAddressZipCode(?string $addressZipCode): self
    {
        $this->addressZipCode = $addressZipCode;

        return $this;
    }

    public function getAddressCountryCode(): ?string
    {
        return $this->addressCountryCode;
    }

    public function setAddressCountryCode(?string $addressCountryCode): self
    {
        $this->addressCountryCode = $addressCountryCode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTagLine(): ?string
    {
        return $this->tagLine;
    }

    public function setTagLine(?string $tagLine): self
    {
        $this->tagLine = $tagLine;

        return $this;
    }

    public function getTrackingCodePage(): ?string
    {
        return $this->trackingCodePage;
    }

    public function setTrackingCodePage(?string $trackingCodePage): self
    {
        $this->trackingCodePage = $trackingCodePage;

        return $this;
    }

    public function getTrackingCodeEvent(): ?string
    {
        return $this->trackingCodeEvent;
    }

    public function setTrackingCodeEvent(?string $trackingCodeEvent): self
    {
        $this->trackingCodeEvent = $trackingCodeEvent;

        return $this;
    }

    public function getPrimaryChannel(): ?string
    {
        return $this->primaryChannel;
    }

    public function setPrimaryChannel(?string $primaryChannel): self
    {
        $this->primaryChannel = $primaryChannel;

        return $this;
    }

    public function getPrimetimeStart(): ?string
    {
        return $this->primetimeStart;
    }

    public function setPrimetimeStart(?string $primetimeStart): self
    {
        $this->primetimeStart = $primetimeStart;

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

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getPdp(): ?bool
    {
        return $this->pdp;
    }

    public function setPdp(?bool $pdp): self
    {
        $this->pdp = $pdp;

        return $this;
    }

    public function getPassportEnabled(): ?bool
    {
        return $this->passportEnabled;
    }

    public function setPassportEnabled(?bool $passportEnabled): self
    {
        $this->passportEnabled = $passportEnabled;

        return $this;
    }

    public function getAnnualPassportQualifyingAmount(): ?int
    {
        return $this->annualPassportQualifyingAmount;
    }

    public function setAnnualPassportQualifyingAmount(?int $annualPassportQualifyingAmount): self
    {
        $this->annualPassportQualifyingAmount = $annualPassportQualifyingAmount;

        return $this;
    }

    /**
     * @return Collection|Audience[]
     */
    public function getAudiences(): Collection
    {
        return $this->audiences;
    }

    public function addAudience(Audience $audience): self
    {
        if (!$this->audiences->contains($audience)) {
            $this->audiences[] = $audience;
            $audience->setStation($this);
        }

        return $this;
    }

    public function removeAudience(Audience $audience): self
    {
        if ($this->audiences->contains($audience)) {
            $this->audiences->removeElement($audience);
            // set the owning side to null (unless already changed)
            if ($audience->getStation() === $this) {
                $audience->setStation(null);
            }
        }

        return $this;
    }
}
