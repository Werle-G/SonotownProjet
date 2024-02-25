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

    #[ORM\OneToMany(targetEntity: ImageConcert::class, mappedBy: 'concert', orphanRemoval: true, cascade: ["persist"])]
    private Collection $imageConcerts;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'concerts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'aimerConcerts')]
    private Collection $users;

    public function __construct()
    {
        $this->imageConcerts = new ArrayCollection();
        $this->users = new ArrayCollection();
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
        return $this->imageConcerts;
    }

    public function addImageConcert(ImageConcert $imageConcert): static
    {
        if (!$this->imageConcerts->contains($imageConcert)) {
            $this->imageConcerts->add($imageConcert);
            $imageConcert->setConcert($this);
        }

        return $this;
    }

    public function removeImageConcert(ImageConcert $imageConcert): static
    {
        if ($this->imageConcerts->removeElement($imageConcert)) {
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
            $user->addAimerConcert($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeAimerConcert($this);
        }

        return $this;
    }
}
