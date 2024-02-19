<?php

namespace App\Entity;

use App\Repository\GenreMusicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    // #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'genreMusicals')]
    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'genreMusicals', orphanRemoval: true, cascade: ["persist"])]

    private Collection $albums;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'genreMusical')]
    private Collection $users;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Album>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        $this->albums->removeElement($album);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGenreMusical($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGenreMusical() === $this) {
                $user->setGenreMusical(null);
            }
        }

        return $this;
    }
}
