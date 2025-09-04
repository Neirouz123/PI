<?php

namespace App\Entity;

use App\Repository\LocalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LocalRepository::class)]
#[ORM\Table(name: 'local')]
class Local
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Venue name is required')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Venue name must be at least {{ limit }} characters long',
        maxMessage: 'Venue name cannot be longer than {{ limit }} characters'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Address is required')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Address must be at least {{ limit }} characters long',
        maxMessage: 'Address cannot be longer than {{ limit }} characters'
    )]
    private ?string $adresse = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: 'Description cannot be longer than {{ limit }} characters'
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Capacity is required')]
    #[Assert\Type(type: 'integer', message: 'Capacity must be a number')]
    #[Assert\Positive(message: 'Capacity must be a positive number')]
    #[Assert\Range(
        min: 1,
        max: 10000,
        notInRangeMessage: 'Capacity must be between {{ min }} and {{ max }}'
    )]
    private ?int $capacite = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Price is required')]
    #[Assert\Type(type: 'float', message: 'Price must be a number')]
    #[Assert\PositiveOrZero(message: 'Price must be zero or positive')]
    #[Assert\Range(
        min: 0,
        max: 999999.99,
        notInRangeMessage: 'Price must be between {{ min }} and {{ max }}'
    )]
    private ?float $prix = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Availability status is required')]
    #[Assert\Type(type: 'bool', message: 'Availability must be true or false')]
    private ?bool $disponible = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
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

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}
