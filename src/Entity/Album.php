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

    #[ORM\OneToMany(targetEntity: Piste::class, mappedBy: 'album', orphanRemoval: true, cascade: ["persist"])]
    private Collection $pistes;

    #[ORM\ManyToOne(inversedBy: 'albums')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: GenreMusical::class, inversedBy: 'albums', cascade: ["persist"])]
    private Collection $genreMusicals;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'aimerAlbums')]
    private Collection $aimerAlbums;

    public function __construct()
    {
        $this->pistes = new ArrayCollection();
        $this->genreMusicals = new ArrayCollection();
        $this->aimerAlbums = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeGenreMusical(GenreMusical $genreMusical): static
    {
        $this->genreMusicals->removeElement($genreMusical);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAimerAlbums(): Collection
    {
        return $this->aimerAlbums;
    }

    public function addAimerAlbum(User $aimerAlbum): static
    {
        if (!$this->aimerAlbums->contains($aimerAlbum)) {
            $this->aimerAlbums->add($aimerAlbum);
        }

        return $this;
    }

    public function removeAimerAlbum(User $aimerAlbum): static
    {
        $this->aimerAlbums->removeElement($aimerAlbum);

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }

}
