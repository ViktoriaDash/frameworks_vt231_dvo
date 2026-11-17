<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Сутність Працівника
 */
#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee implements JsonSerializable
{
    /**
     * @var int|null Унікальний ідентифікатор
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null Ім'я працівника
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string|null Посада
     */
    #[ORM\Column(length: 255)]
    private ?string $position = null;

    /**
     * @var string|null Номер телефону
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

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

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Серіалізація сутності в JSON
     * 
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'position' => $this->position,
            'phone'    => $this->phone,
        ];
    }
}