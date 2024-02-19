<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    // #[ORM\OneToMany(targetEntity: Piste::class, mappedBy: 'album', orphanRemoval: true)]
    #[ORM\OneToMany(targetEntity: Piste::class, mappedBy: 'album', orphanRemoval: true, cascade: ["persist"])]
    private Collection $pistes;

    #[ORM\ManyToMany(targetEntity: GenreMusical::class, mappedBy: 'albums')]
    private Collection $genreMusicals;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'aimers')]
    private Collection $aimers;

    #[ORM\ManyToOne(inversedBy: 'albums')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->pistes = new ArrayCollection();
        $this->genreMusicals = new ArrayCollection();
        $this->aimers = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Piste>
     */
    public function getPistes(): Collection
    {
        return $this->pistes;
    }

    public function addPiste(Piste $piste): static
    {
        if (!$this->pistes->contains($piste)) {
            $this->pistes->add($piste);
            $piste->setAlbum($this);
        }

        return $this;
    }

    public function removePiste(Piste $piste): static
    {
        if ($this->pistes->removeElement($piste)) {
            // set the owning side to null (unless already changed)
            if ($piste->getAlbum() === $this) {
                $piste->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GenreMusical>
     */
    public function getGenreMusicals(): Collection
    {
        return $this->genreMusicals;
    }

    public function addGenreMusical(GenreMusical $genreMusical): static
    {
        if (!$this->genreMusicals->contains($genreMusical)) {
            $this->genreMusicals->add($genreMusical);
            $genreMusical->addAlbum($this);
        }

        return $this;
    }

    public function removeGenreMusical(GenreMusical $genreMusical): static
    {
        if ($this->genreMusicals->removeElement($genreMusical)) {
            $genreMusical->removeAlbum($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAimers(): Collection
    {
        return $this->aimers;
    }

    public function addAimer(User $aimer): static
    {
        if (!$this->aimers->contains($aimer)) {
            $this->aimers->add($aimer);
            $aimer->addAimer($this);
        }

        return $this;
    }

    public function removeAimer(User $aimer): static
    {
        if ($this->aimers->removeElement($aimer)) {
            $aimer->removeAimer($this);
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

    // public function __toString()
    // {
    //     return $this->nomAlbum ?? '';
    // }
}
