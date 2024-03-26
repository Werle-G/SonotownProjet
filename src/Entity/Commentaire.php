<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ban = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $commenter = null;

    #[ORM\ManyToOne(inversedBy: 'repondres', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $repondre = null;

    public function __construct()
    {
        $this->dateCreation = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function isBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(bool $ban): static
    {
        $this->ban = $ban;

        return $this;
    }

    public function getCommenter(): ?User
    {
        return $this->commenter;
    }

    public function setCommenter(?User $commenter): static
    {
        $this->commenter = $commenter;

        return $this;
    }

    public function getRepondre(): ?User
    {
        return $this->repondre;
    }

    public function setRepondre(?User $repondre): static
    {
        $this->repondre = $repondre;

        return $this;
    }
}
