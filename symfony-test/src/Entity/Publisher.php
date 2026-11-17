<?php

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Сутність Видавництва
 */
#[ORM\Entity(repositoryClass: PublisherRepository::class)]
class Publisher implements JsonSerializable
{
    /**
     * @var int|null Унікальний ідентифікатор
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null Назва видавництва
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string|null Адреса видавництва
     */
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    /**
     * @var string|null Вебсайт видавництва
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

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
        $this->name =$name;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address =$address;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website =$website;
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
            'id'      => $this->id,
            'name'    => $this->name,
            'address' => $this->address,
            'website' => $this->website,
        ];
    }
}