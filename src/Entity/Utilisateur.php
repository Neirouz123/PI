<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
#[UniqueEntity(fields: ['username'], message: 'This username is already taken')]
#[UniqueEntity(fields: ['email'], message: 'This email is already registered')]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Username is required')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Username must be at least {{ limit }} characters long',
        maxMessage: 'Username cannot be longer than {{ limit }} characters'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9_]+$/',
        message: 'Username can only contain letters, numbers and underscores'
    )]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Please enter a valid email address')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Email cannot be longer than {{ limit }} characters'
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Length(
        min: 6,
        minMessage: 'Password must be at least {{ limit }} characters long'
    )]
    private ?string $password_hash = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Role is required')]
    #[Assert\Choice(
        choices: ['ADMIN', 'CLIENT'],
        message: 'Role must be either ADMIN or CLIENT'
    )]
    private ?string $role = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
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

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): static
    {
        $this->password_hash = $password_hash;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
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
}
