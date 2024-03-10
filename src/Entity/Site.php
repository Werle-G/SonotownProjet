<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(targetEntity: Reseau::class, mappedBy: 'site')]
    private Collection $reseaus;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'sites')]
    private Collection $users;


    public function __construct()
    {
        $this->reseaus = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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
            $reseau->setSite($this);
        }

        return $this;
    }

    public function removeReseau(Reseau $reseau): static
    {
        if ($this->reseaus->removeElement($reseau)) {
            // set the owning side to null (unless already changed)
            if ($reseau->getSite() === $this) {
                $reseau->setSite(null);
            }
        }

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
            $user->addSite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeSite($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom; 
    }

    

}
