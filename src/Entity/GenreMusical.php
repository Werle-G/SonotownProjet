<?php

namespace App\Entity;

use App\Repository\GenreMusicalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreMusicalRepository::class)]
class GenreMusical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomGenreMusical = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGenreMusical(): ?string
    {
        return $this->nomGenreMusical;
    }

    public function setNomGenreMusical(string $nomGenreMusical): static
    {
        $this->nomGenreMusical = $nomGenreMusical;

        return $this;
    }
}
