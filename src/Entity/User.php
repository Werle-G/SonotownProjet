<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

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

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'suivres', mappedBy: 'suivres')]
    private Collection $suivres;

    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'aimers')]
    private Collection $aimers;

    // Albums crées par un utilisateur 
    #[ORM\OneToMany(targetEntity: Album::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $albums;

    // Genre musical d'un utilisateur
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?GenreMusical $genreMusical = null;

    // Concerts joués par un utilisateur
    #[ORM\OneToMany(targetEntity: Concert::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $concerts;

    // Posts crée par un utilisateur
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $posts;

    public function __construct()
    {
        $this->suivres = new ArrayCollection();
        $this->aimers = new ArrayCollection();

        $this->albums = new ArrayCollection();
        // Pointe la collection qu'un utilisateur a crée

        // Concerts joués par un utilisateur
        $this->concerts = new ArrayCollection();

        // Posts des utilisateurs
        $this->posts = new ArrayCollection();
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
        // $roles = json_decode($this->roles, true);
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        // $encoder = new JsonEncoder();
        // $this->roles = $encoder->encode($roles, JsonEncoder::FORMAT);

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
    public function getAlbums(): Collection
    {
        return $this->albums;
    }
    
    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->setUser($this);
        }
    
        return $this;
    }
    
    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getUser() === $this) {
                $album->setUser(null);
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
    public function getConcerts(): Collection
    {
        return $this->concerts;
    }

    public function addConcert(Concert $concert): static
    {
        if (!$this->concerts->contains($concert)) {
            $this->concerts->add($concert);
            $concert->setUser($this);
        }

        return $this;
    }

    public function removeConcert(Concert $concert): static
    {
        if ($this->concerts->removeElement($concert)) {
            // set the owning side to null (unless already changed)
            if ($concert->getUser() === $this) {
                $concert->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPoster(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }
}
