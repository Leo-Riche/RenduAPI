<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Attribute\Groups;

use App\Repository\TraitementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: "Accès refusé : vous n'êtes pas autorisé à consulter la liste des traitements."
        ),
        new Post(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: "Accès refusé : vous n'êtes pas autorisé à créer un traitement."
        ),
        new Get(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: 'Accès refusé : vous ne pouvez pas consulter ce traitement.'
        ),
        new Patch(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: 'Accès refusé : vous ne pouvez pas modifier un traitement.'
        ),
        new Delete(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: 'Accès refusé : vous ne pouvez pas supprimer ce traitement.'
        ),
    ],
)]

#[ORM\Entity(repositoryClass: TraitementRepository::class)]
class Traitement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read','write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read','write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['read','write'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read','write'])]
    private ?string $duration = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}
