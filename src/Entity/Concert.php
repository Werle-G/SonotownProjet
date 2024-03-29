<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
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
}
