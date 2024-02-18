<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomAlbum = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $imageAlbum = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSortieAlbum = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbPistes = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ban = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAlbum(): ?string
    {
        return $this->nomAlbum;
    }

    public function setNomAlbum(string $nomAlbum): static
    {
        $this->nomAlbum = $nomAlbum;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageAlbum(): ?string
    {
        return $this->imageAlbum;
    }

    public function setImageAlbum(?string $imageAlbum): static
    {
        $this->imageAlbum = $imageAlbum;

        return $this;
    }

    public function getDateSortieAlbum(): ?\DateTimeInterface
    {
        return $this->dateSortieAlbum;
    }

    public function setDateSortieAlbum(?\DateTimeInterface $dateSortieAlbum): static
    {
        $this->dateSortieAlbum = $dateSortieAlbum;

        return $this;
    }

    public function getNbPistes(): ?int
    {
        return $this->nbPistes;
    }

    public function setNbPistes(?int $nbPistes): static
    {
        $this->nbPistes = $nbPistes;

        return $this;
    }

    public function isBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(?bool $ban): static
    {
        $this->ban = $ban;

        return $this;
    }
}
