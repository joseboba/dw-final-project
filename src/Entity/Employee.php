<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column(length: 255)]
    private ?string $picture_url = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Positions $position = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 2)]
    #[Assert\NotBlank(message: 'El salario es requerido')]
    #[Assert\Range(min: 3000, minMessage: 'El salario debe ser al menos de Q3,000.00')]
    private ?string $salary = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Store $store = null;

    #[ORM\Column(length: 255)]
    private ?string $public_image_id = null;

    /**
     * @var Collection<int, EmployeeAchievement>
     */
    #[ORM\OneToMany(targetEntity: EmployeeAchievement::class, mappedBy: 'employee')]
    private Collection $employeeAchievements;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El nombre es requerido')]
    #[Assert\Length(min: 1, max: 100, maxMessage: 'Máximo 100 cáracteres')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    public function __construct()
    {
        $this->employeeAchievements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->picture_url;
    }

    public function setPictureUrl(string $picture_url): static
    {
        $this->picture_url = $picture_url;

        return $this;
    }

    public function getPosition(): ?Positions
    {
        return $this->position;
    }

    public function setPosition(?Positions $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): static
    {
        $this->store = $store;

        return $this;
    }

    public function getPublicImageId(): ?string
    {
        return $this->public_image_id;
    }

    public function setPublicImageId(string $public_image_id): static
    {
        $this->public_image_id = $public_image_id;

        return $this;
    }

    /**
     * @return Collection<int, EmployeeAchievement>
     */
    public function getEmployeeAchievements(): Collection
    {
        return $this->employeeAchievements;
    }

    public function addEmployeeAchievement(EmployeeAchievement $employeeAchievement): static
    {
        if (!$this->employeeAchievements->contains($employeeAchievement)) {
            $this->employeeAchievements->add($employeeAchievement);
            $employeeAchievement->setEmployee($this);
        }

        return $this;
    }

    public function removeEmployeeAchievement(EmployeeAchievement $employeeAchievement): static
    {
        if ($this->employeeAchievements->removeElement($employeeAchievement)) {
            // set the owning side to null (unless already changed)
            if ($employeeAchievement->getEmployee() === $this) {
                $employeeAchievement->setEmployee(null);
            }
        }

        return $this;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }
}
