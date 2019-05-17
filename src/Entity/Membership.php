<?php

namespace CascadePublicMedia\PbsApiExplorer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CascadePublicMedia\PbsApiExplorer\Repository\MembershipRepository")
 */
class Membership
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $offer;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalMetadata;

    /**
     * @ORM\Column(type="boolean")
     */
    private $provisional;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $expireDate;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $activationDate;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $gracePeriod;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $createDate;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="CascadePublicMedia\PbsApiExplorer\Entity\PbsProfile",
     *     inversedBy="memberships",
     *     cascade={"persist", "merge"}
     * )
     */
    private $pbsProfile;

    public function __toString()
    {
        return $this->email . ' (' . $this->id . ')';
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getOffer(): ?string
    {
        return $this->offer;
    }

    public function setOffer(?string $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getAdditionalMetadata(): ?string
    {
        return $this->additionalMetadata;
    }

    public function setAdditionalMetadata(?string $additionalMetadata): self
    {
        $this->additionalMetadata = $additionalMetadata;

        return $this;
    }

    public function getProvisional(): ?bool
    {
        return $this->provisional;
    }

    public function setProvisional(bool $provisional): self
    {
        $this->provisional = $provisional;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getExpireDate(): ?\DateTimeInterface
    {
        return $this->expireDate;
    }

    public function setExpireDate(\DateTimeInterface $expireDate): self
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    public function getActivationDate(): ?\DateTimeInterface
    {
        return $this->activationDate;
    }

    public function setActivationDate(?\DateTimeInterface $activationDate): self
    {
        $this->activationDate = $activationDate;

        return $this;
    }

    public function getGracePeriod(): ?\DateTimeInterface
    {
        return $this->gracePeriod;
    }

    public function setGracePeriod(\DateTimeInterface $gracePeriod): self
    {
        $this->gracePeriod = $gracePeriod;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getPbsProfile(): ?PbsProfile
    {
        return $this->pbsProfile;
    }

    public function setPbsProfile(?PbsProfile $pbsProfile): self
    {
        $this->pbsProfile = $pbsProfile;

        return $this;
    }
}
