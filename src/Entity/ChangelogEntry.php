<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\ChangelogEntryRepository")
 */
class ChangelogEntry
{
    /**
     * The human readable name for this class.
     */
    public const NAME = 'changelog';

    /**
     * The Media Manage API endpoint for this entity.
     */
    public const ENDPOINT = 'changelog';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $activity;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $updatedFields = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="guid", length=255, nullable=true)
     */
    private $resourceId;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $timestamp;

    public function __toString()
    {
        return $this->activity;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getUpdatedFields(): ?array
    {
        return $this->updatedFields;
    }

    public function setUpdatedFields(?array $updatedFields): self
    {
        $this->updatedFields = $updatedFields;

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

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    public function setResourceId(?string $resourceId): self
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
