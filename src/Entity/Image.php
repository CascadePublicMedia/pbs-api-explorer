<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profile;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $updated;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Asset",
     *     inversedBy="images",
     *     cascade={"persist", "merge"}
     * )
     */
    private $asset;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Franchise",
     *     inversedBy="images",
     *     cascade={"persist", "merge"}
     * )
     */
    private $franchise;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Show",
     *     inversedBy="images",
     *     cascade={"persist", "merge"}
     * )
     */
    private $show;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Station",
     *     inversedBy="images",
     *     cascade={"persist", "merge"}
     * )
     */
    private $station;

    /**
     * @var object
     */
    private $parent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(string $profile): self
    {
        $this->profile = $profile;

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

    public function getAsset(): ?Asset
    {
        return $this->asset;
    }

    public function setAsset(?Asset $asset): self
    {
        $this->asset = $asset;

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

    public function getShow(): ?Show
    {
        return $this->show;
    }

    public function setShow(?Show $show): self
    {
        $this->show = $show;

        return $this;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

        return $this;
    }

    public function getParent()
    {
        foreach (['asset', 'franchise', 'show', 'station'] as $type) {
            if ($this->{$type}) {
                return $this->{$type};
            }
        }

        return NULL;
    }
}
