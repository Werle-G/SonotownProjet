<?php

namespace App\Entity;

use App\Repository\ImageConcertRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageConcertRepository::class)]
class ImageConcert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomImage = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $alt = null;

    #[ORM\ManyToOne(targetEntity: Concert::class, inversedBy: 'imageConcerts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Concert $concert = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomImage(): ?string
    {
        return $this->nomImage;
    }

    public function setNomImage(string $nomImage): static
    {
        $this->nomImage = $nomImage;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

    public function getConcert(): ?Concert
    {
        return $this->concert;
    }

    public function setConcert(?Concert $concert): static
    {
        $this->concert = $concert;

        return $this;
    }
}
