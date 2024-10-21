<?php

namespace App\Entity;

use App\Repository\EmployeeAchievementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeAchievementRepository::class)]
class EmployeeAchievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La descripción es requerida')]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: 'La descripción necesita al menos 10 cáracteres',
        maxMessage: 'La descripción puede ser máximo de 255 cáracteres'
    )]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $achievement_type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $achievement_date = null;

    #[ORM\ManyToOne(inversedBy: 'employeeAchievements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function isAchievementType(): ?bool
    {
        return $this->achievement_type;
    }

    public function setAchievementType(bool $achievement_type): static
    {
        $this->achievement_type = $achievement_type;

        return $this;
    }

    public function getAchievementDate(): ?\DateTimeInterface
    {
        return $this->achievement_date;
    }

    public function setAchievementDate(\DateTimeInterface $achievement_date): static
    {
        $this->achievement_date = $achievement_date;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }
}
