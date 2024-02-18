<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConcertRepository::class)]
class Concert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateConcert = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptionConcert = null;

    #[ORM\OneToMany(targetEntity: ImageConcert::class, mappedBy: 'concert', orphanRemoval: true)]
    private Collection $ImageConcerts;

    #[ORM\ManyToOne(inversedBy: 'jouers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->ImageConcerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateConcert(): ?\DateTimeInterface
    {
        return $this->dateConcert;
    }

    public function setDateConcert(\DateTimeInterface $dateConcert): static
    {
        $this->dateConcert = $dateConcert;

        return $this;
    }

    public function getDescriptionConcert(): ?string
    {
        return $this->descriptionConcert;
    }

    public function setDescriptionConcert(string $descriptionConcert): static
    {
        $this->descriptionConcert = $descriptionConcert;

        return $this;
    }

    /**
     * @return Collection<int, ImageConcert>
     */
    public function getImageConcerts(): Collection
    {
        return $this->ImageConcerts;
    }

    public function addImageConcert(ImageConcert $imageConcert): static
    {
        if (!$this->ImageConcerts->contains($imageConcert)) {
            $this->ImageConcerts->add($imageConcert);
            $imageConcert->setConcert($this);
        }

        return $this;
    }

    public function removeImageConcert(ImageConcert $imageConcert): static
    {
        if ($this->ImageConcerts->removeElement($imageConcert)) {
            // set the owning side to null (unless already changed)
            if ($imageConcert->getConcert() === $this) {
                $imageConcert->setConcert(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
