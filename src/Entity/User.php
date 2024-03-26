<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
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
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 50, unique: true, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $dateCreationCompte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(length: 100, nullable: true, unique: true)]
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

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'followers')]
    private Collection $follows;
    
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'follows')]
    private Collection $followers;

    // Albums crées par un utilisateur 
    #[ORM\OneToMany(targetEntity: Album::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $albums;

    // Concerts joués par un utilisateur
    #[ORM\OneToMany(targetEntity: Concert::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $concerts;

    #[ORM\ManyToMany(targetEntity: Concert::class, inversedBy: 'users')]
    private Collection $aimerConcerts;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private string $slug;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'commenter', cascade: ['persist'])]
    private Collection $commentaires;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'repondre')]
    private Collection $repondres;

    #[ORM\OneToMany(targetEntity: Playlist::class, mappedBy: 'user', cascade: ['persist'], orphanRemoval: true)]
    private Collection $playlists;

    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'aimerAlbums')]
    private Collection $aimerAlbums;
        
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hostedDomain = null;

    #[ORM\OneToMany(targetEntity: Reseau::class, mappedBy: 'user', cascade: ['persist'], orphanRemoval: true)]
    private Collection $reseaus;

    #[ORM\ManyToMany(targetEntity: Site::class, inversedBy: 'users')]
    private Collection $sites;

    public function __construct()
    {
        
        $this->follows = new ArrayCollection();
        $this->followers = new ArrayCollection();

        $this->albums = new ArrayCollection();
        // Pointe la collection qu'un utilisateur a crée

        // Concerts joués par un utilisateur
        $this->concerts = new ArrayCollection();

        $this->aimerConcerts = new ArrayCollection();

        $this->dateCreationCompte = new DateTimeImmutable();
        
        $this->commentaires = new ArrayCollection();
        $this->repondres = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->aimerAlbums = new ArrayCollection();
        $this->reseaus = new ArrayCollection();
        $this->sites = new ArrayCollection();
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

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
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

    public function getDateCreationCompte(): ?\DateTimeImmutable
    {
        return $this->dateCreationCompte;
    }

    public function setDateCreationCompte(\DateTimeImmutable $dateCreationCompte): static
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
     * @return Collection<int, User>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function addFollow(User $follow): static
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
            $follow->addFollower($this);  
        }

        return $this;
    }

    public function removeFollow(User $follow): static
    {
        if ($this->follows->removeElement($follow)) {
            $follow->removeFollower($this);  
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(User $follower): static
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
            $follower->addFollow($this);  
        }

        return $this;
    }

    public function removeFollower(User $follower): static
    {
        if ($this->followers->removeElement($follower)) {
            $follower->removeFollow($this);  
        }

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
     * @return Collection<int, Concert>
     */
    public function getAimerConcerts(): Collection
    {
        return $this->aimerConcerts;
    }

    public function addAimerConcert(Concert $aimerConcert): static
    {
        if (!$this->aimerConcerts->contains($aimerConcert)) {
            $this->aimerConcerts->add($aimerConcert);
        }

        return $this;
    }

    public function removeAimerConcert(Concert $aimerConcert): static
    {
        $this->aimerConcerts->removeElement($aimerConcert);

        return $this;
    }

    // Slug

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setCommenter($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCommenter() === $this) {
                $commentaire->setCommenter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getRepondres(): Collection
    {
        return $this->repondres;
    }

    public function addRepondre(Commentaire $repondre): static
    {
        if (!$this->repondres->contains($repondre)) {
            $this->repondres->add($repondre);
            $repondre->setRepondre($this);
        }

        return $this;
    }

    public function removeRepondre(Commentaire $repondre): static
    {
        if ($this->repondres->removeElement($repondre)) {
            // set the owning side to null (unless already changed)
            if ($repondre->getRepondre() === $this) {
                $repondre->setRepondre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setUser($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getUser() === $this) {
                $playlist->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAimerAlbums(): Collection
    {
        return $this->aimerAlbums;
    }

    public function addAimerAlbum(Album $aimerAlbum): static
    {
        if (!$this->aimerAlbums->contains($aimerAlbum)) {
            $this->aimerAlbums->add($aimerAlbum);
            $aimerAlbum->addAimerAlbum($this);
        }

        return $this;
    }

    public function removeAimerAlbum(Album $aimerAlbum): static
    {
        if ($this->aimerAlbums->removeElement($aimerAlbum)) {
            $aimerAlbum->removeAimerAlbum($this);
        }

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): static
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getHostedDomain(): ?string
    {
        return $this->hostedDomain;
    }

    public function setHostedDomain(?string $hostedDomain): static
    {
        $this->hostedDomain = $hostedDomain;

        return $this;
    }

    /**
     * @return Collection<int, Reseau>
     */
    public function getReseaus(): Collection
    {
        return $this->reseaus;
    }

    public function addReseau(Reseau $reseau): static
    {
        if (!$this->reseaus->contains($reseau)) {
            $this->reseaus->add($reseau);
            $reseau->setUser($this);
        }

        return $this;
    }

    public function removeReseau(Reseau $reseau): static
    {
        if ($this->reseaus->removeElement($reseau)) {
            // set the owning side to null (unless already changed)
            if ($reseau->getUser() === $this) {
                $reseau->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Site>
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): static
    {
        if (!$this->sites->contains($site)) {
            $this->sites->add($site);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        $this->sites->removeElement($site);

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }

}
