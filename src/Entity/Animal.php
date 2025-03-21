<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Attribute\Groups;

use App\Repository\AnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    forceEager: false,
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
    new GetCollection(
        security: "is_granted('ROLE_DIRECTOR')",
        securityMessage: 'Accès refusé : vous n\'êtes pas autorisé à consulter la liste des animaux.'
    ),
    new Post(
        security: "is_granted('ROLE_ASSISTANT')",
        securityMessage: 'Accès refusé : vous n\'êtes pas autorisé à enregistrer un nouvel animal.'
    ),
    new Get(
        security: "is_granted('ROLE_ASSISTANT') or object.owner == user",
        securityMessage: 'Accès refusé : vous ne pouvez pas consulter cet animal.'
    ),
    new Patch(
        security: "is_granted('ROLE_DIRECTOR') or object.owner == user",
        securityMessage: 'Accès refusé : vous ne pouvez pas modifier cet animal.'
    ),
    new Delete(
        security: "is_granted('ROLE_DIRECTOR') or object.owner == user",
        securityMessage: 'Accès refusé : vous ne pouvez pas supprimer cet animal.'
    ),
],

)]

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read','write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read','write'])]
    private ?string $species = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['read','write'])]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read','write'])]
    private ?Media $picture = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read','write'])]
    private ?Client $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPicture(): ?Media
    {
        return $this->picture;
    }

    public function setPicture(Media $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getOwner(): ?Client
    {
        return $this->owner;
    }

    public function setOwner(?Client $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
