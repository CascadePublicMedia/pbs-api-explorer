<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\AssetTagRepository")
 */
class AssetTag
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Asset",
     *     mappedBy="tags",
     *     cascade={"persist", "merge"}
     * )
     */
    private $assets;

    /**
     * @ORM\ManyToMany(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\RemoteAsset", mappedBy="tags")
     */
    private $remoteAssets;

    public function __construct()
    {
        $this->assets = new ArrayCollection();
        $this->remoteAssets = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id;
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

    /**
     * @return Collection|Asset[]
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): self
    {
        if (!$this->assets->contains($asset)) {
            $this->assets[] = $asset;
            $asset->addTag($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): self
    {
        if ($this->assets->contains($asset)) {
            $this->assets->removeElement($asset);
            $asset->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|RemoteAsset[]
     */
    public function getRemoteAssets(): Collection
    {
        return $this->remoteAssets;
    }

    public function addRemoteAsset(RemoteAsset $remoteAsset): self
    {
        if (!$this->remoteAssets->contains($remoteAsset)) {
            $this->remoteAssets[] = $remoteAsset;
            $remoteAsset->addTag($this);
        }

        return $this;
    }

    public function removeRemoteAsset(RemoteAsset $remoteAsset): self
    {
        if ($this->remoteAssets->contains($remoteAsset)) {
            $this->remoteAssets->removeElement($remoteAsset);
            $remoteAsset->removeTag($this);
        }

        return $this;
    }
}
