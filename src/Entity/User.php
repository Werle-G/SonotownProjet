<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreationCompte = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomArtiste = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biographie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreationGroupe = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $imageCouverture = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ban = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'suivres')]
    private Collection $suivres;

    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'aimers')]
    private Collection $aimers;

    #[ORM\OneToMany(targetEntity: Album::class, mappedBy: 'produirs', orphanRemoval: true)]
    private Collection $produirs;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?GenreMusical $genreMusical = null;

    #[ORM\OneToMany(targetEntity: Concert::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $jouers;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $poster;

    public function __construct()
    {
        $this->suivres = new ArrayCollection();
        $this->aimers = new ArrayCollection();
        $this->produirs = new ArrayCollection();
        $this->jouers = new ArrayCollection();
        $this->poster = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDateCreationCompte(): ?\DateTimeInterface
    {
        return $this->dateCreationCompte;
    }

    public function setDateCreationCompte(?\DateTimeInterface $dateCreationCompte): static
    {
        $this->dateCreationCompte = $dateCreationCompte;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getNomArtiste(): ?string
    {
        return $this->nomArtiste;
    }

    public function setNomArtiste(?string $nomArtiste): static
    {
        $this->nomArtiste = $nomArtiste;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): static
    {
        $this->biographie = $biographie;

        return $this;
    }

    public function getDateCreationGroupe(): ?\DateTimeInterface
    {
        return $this->dateCreationGroupe;
    }

    public function setDateCreationGroupe(?\DateTimeInterface $dateCreationGroupe): static
    {
        $this->dateCreationGroupe = $dateCreationGroupe;

        return $this;
    }

    public function getImageCouverture(): ?string
    {
        return $this->imageCouverture;
    }

    public function setImageCouverture(?string $imageCouverture): static
    {
        $this->imageCouverture = $imageCouverture;

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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSuivres(): Collection
    {
        return $this->suivres;
    }

    public function addSuivre(self $suivre): static
    {
        if (!$this->suivres->contains($suivre)) {
            $this->suivres->add($suivre);
        }

        return $this;
    }

    public function removeSuivre(self $suivre): static
    {
        $this->suivres->removeElement($suivre);

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAimers(): Collection
    {
        return $this->aimers;
    }

    public function addAimer(Album $aimer): static
    {
        if (!$this->aimers->contains($aimer)) {
            $this->aimers->add($aimer);
        }

        return $this;
    }

    public function removeAimer(Album $aimer): static
    {
        $this->aimers->removeElement($aimer);

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getProduirs(): Collection
    {
        return $this->produirs;
    }

    public function addProduir(Album $produir): static
    {
        if (!$this->produirs->contains($produir)) {
            $this->produirs->add($produir);
            $produir->setProduirs($this);
        }

        return $this;
    }

    public function removeProduir(Album $produir): static
    {
        if ($this->produirs->removeElement($produir)) {
            // set the owning side to null (unless already changed)
            if ($produir->getProduirs() === $this) {
                $produir->setProduirs(null);
            }
        }

        return $this;
    }

    public function getGenreMusical(): ?GenreMusical
    {
        return $this->genreMusical;
    }

    public function setGenreMusical(?GenreMusical $genreMusical): static
    {
        $this->genreMusical = $genreMusical;

        return $this;
    }

    /**
     * @return Collection<int, Concert>
     */
    public function getJouers(): Collection
    {
        return $this->jouers;
    }

    public function addJouer(Concert $jouer): static
    {
        if (!$this->jouers->contains($jouer)) {
            $this->jouers->add($jouer);
            $jouer->setUser($this);
        }

        return $this;
    }

    public function removeJouer(Concert $jouer): static
    {
        if ($this->jouers->removeElement($jouer)) {
            // set the owning side to null (unless already changed)
            if ($jouer->getUser() === $this) {
                $jouer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPoster(): Collection
    {
        return $this->poster;
    }

    public function addPoster(Post $poster): static
    {
        if (!$this->poster->contains($poster)) {
            $this->poster->add($poster);
            $poster->setUser($this);
        }

        return $this;
    }

    public function removePoster(Post $poster): static
    {
        if ($this->poster->removeElement($poster)) {
            // set the owning side to null (unless already changed)
            if ($poster->getUser() === $this) {
                $poster->setUser(null);
            }
        }

        return $this;
    }
}
