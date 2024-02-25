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

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'genreMusical')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'genreMusicals')]
    private Collection $albums;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->albums = new ArrayCollection();
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

    public function __toString(){

        return $this->nomGenreMusical;
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
            $album->addGenreMusical($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeGenreMusical($this);
        }

        return $this;
    }
}
