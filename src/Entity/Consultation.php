<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Enum\ConsultationStatus;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'status' => 'exact', 'isPaid' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['date'])]

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_VETERINARIAN') or is_granted('ROLE_ASSISTANT') or is_granted('ROLE_DIRECTOR')",
            securityMessage: "Accès refusé : vous n'êtes pas autorisé à consulter la liste des consultations."
        ),
        new Post(
            security: "is_granted('ROLE_ASSISTANT')",
            securityMessage: "Accès refusé : vous n'êtes pas autorisé à créer une consultation."
        ),
        new Get(
            security: "is_granted('ROLE_ASSISTANT') or is_granted('ROLE_VETERINARIAN') or object == user",
            securityMessage: 'Accès refusé : vous ne pouvez pas consulter cette consultation.'
        ),
        new Patch(
            security: "(is_granted('ROLE_ASSISTANT') or is_granted('ROLE_VETERINARIAN') or object == user) and object.status()",
            securityMessage: 'Accès refusé : vous ne pouvez pas modifier une consultation.'
        ),
        new Delete(
            security: "is_granted('ROLE_DIRECTOR') or object.owner == user",
            securityMessage: 'Accès refusé : vous ne pouvez pas supprimer cette consultation.'
        ),
    ],
)]

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read','write'])]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read','write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read','write'])]
    private ?string $reason = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read','write'])]
    private ?Animal $animal = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read','write'])]
    private ?User $assistant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read','write'])]
    private ?User $veterinarian = null;

    #[ORM\Column(type: 'string', enumType: ConsultationStatus::class,length: 255)]
    #[Groups(['read','write'])]
    private ?ConsultationStatus $status = null;

    /**
     * @var Collection<int, Traitement>
     */
    #[ORM\ManyToMany(targetEntity: Traitement::class)]
    #[Groups(['read','write'])]
    private Collection $treatments;

    #[ORM\Column]
    #[Groups(['read','write'])]
    private ?bool $isPaid = null;

    public function __construct()
    {
        $this->treatments = new ArrayCollection();
        $this->createdDate = new \DateTime(
            datetime: 'now',
            timezone: new \DateTimeZone('Europe/Paris')
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getAssistant(): ?User
    {
        return $this->assistant;
    }

    public function setAssistant(?User $assistant): static
    {
        if ($assistant && !in_array('ROLE_ASSISTANT', $assistant->getRoles(), true)) {
            throw new \InvalidArgumentException("L'utilisateur sélectionné n'a pas le rôle assistant.");
        }

        $this->assistant = $assistant;
        return $this;
    }

    public function getVeterinarian(): ?User
    {
        return $this->veterinarian;
    }

    public function setVeterinarian(?User $veterinarian): static
    {
        if ($veterinarian && !in_array('ROLE_VETERINARIAN', $veterinarian->getRoles(), true)) {
            throw new \InvalidArgumentException("L'utilisateur sélectionné n'a pas le rôle vétérinaire.");
        }

        $this->veterinarian = $veterinarian;
        return $this;
    }

    public function getStatus(): ?ConsultationStatus
    {
        return $this->status;
    }

    public function setStatus(ConsultationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Traitement>
     */
    public function getTreatments(): Collection
    {
        return $this->treatments;
    }

    public function addTreatment(Traitement $treatment): static
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments->add($treatment);
        }

        return $this;
    }

    public function removeTreatment(Traitement $treatment): static
    {
        $this->treatments->removeElement($treatment);

        return $this;
    }

    public function status(): bool
    {
        return $this->status !== ConsultationStatus::TERMINE;
    }

    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }
}
