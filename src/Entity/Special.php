<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\SpecialRepository")
 */
class Special
{
    /**
     * The Media Manage API endpoint for this entity.
     */
    public const ENDPOINT = 'specials';

    /**
     * The human readable name for this class.
     */
    public const NAME = 'special';

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
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleSortable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tmsId;

    /**
     * @ORM\Column(type="string", length=90)
     */
    private $descriptionShort;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionLong;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $premiered;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $encored;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nola;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $language;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fullLengthAsset;

    /**
     * @ORM\ManyToOne(targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\Show", inversedBy="specials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $show;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getTmsId(): ?string
    {
        return $this->tmsId;
    }

    public function setTmsId(?string $tmsId): self
    {
        $this->tmsId = $tmsId;

        return $this;
    }

    public function getDescriptionShort(): ?string
    {
        return $this->descriptionShort;
    }

    public function setDescriptionShort(string $descriptionShort): self
    {
        $this->descriptionShort = $descriptionShort;

        return $this;
    }

    public function getDescriptionLong(): ?string
    {
        return $this->descriptionLong;
    }

    public function setDescriptionLong(string $descriptionLong): self
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

    public function getEncored(): ?\DateTimeInterface
    {
        return $this->encored;
    }

    public function setEncored(?\DateTimeInterface $encored): self
    {
        $this->encored = $encored;

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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

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

    public function getFullLengthAsset(): ?bool
    {
        return $this->fullLengthAsset;
    }

    public function setFullLengthAsset(?bool $fullLengthAsset): self
    {
        $this->fullLengthAsset = $fullLengthAsset;

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
}
