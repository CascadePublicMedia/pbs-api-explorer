<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(
 *     repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\HeadendRepository"
 * )
 */
class Headend
{
    /**
     * The human readable name for this class.
     */
    public const NAME = 'channel';

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="array")
     */
    private $feeds = [];

    public function __toString()
    {
        return $this->name;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFeeds(): ?array
    {
        return $this->feeds;
    }

    public function setFeeds(array $feeds): self
    {
        $this->feeds = $feeds;

        return $this;
    }
}
