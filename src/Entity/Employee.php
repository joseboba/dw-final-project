<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column(length: 255)]
    private ?string $picture_url = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Positions $position = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 2)]
    private ?string $salary = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Store $store = null;

    #[ORM\Column(length: 255)]
    private ?string $public_image_id = null;

    /**
     * @var Collection<int, EmployeeAchievement>
     */
    #[ORM\OneToMany(targetEntity: EmployeeAchievement::class, mappedBy: 'employee')]
    private Collection $employeeAchievements;

    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

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

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }
}
