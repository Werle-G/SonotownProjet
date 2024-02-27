<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomPlaylist = null;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Piste::class, inversedBy: 'playlists')]
    private Collection $ajouter;

    public function __construct()
    {
        $this->ajouter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlaylist(): ?string
    {
        return $this->nomPlaylist;
    }

    public function setNomPlaylist(string $nomPlaylist): static
    {
        $this->nomPlaylist = $nomPlaylist;

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

    /**
     * @return Collection<int, Piste>
     */
    public function getAjouter(): Collection
    {
        return $this->ajouter;
    }

    public function addAjouter(Piste $ajouter): static
    {
        if (!$this->ajouter->contains($ajouter)) {
            $this->ajouter->add($ajouter);
        }

        return $this;
    }

    public function removeAjouter(Piste $ajouter): static
    {
        $this->ajouter->removeElement($ajouter);

        return $this;
    }
}
