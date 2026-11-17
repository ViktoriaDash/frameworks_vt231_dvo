<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Сутність Відгуку
 */
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review implements JsonSerializable
{
    /**
     * @var int|null Унікальний ідентифікатор
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null Ім'я читача, який залишив відгук
     */
    #[ORM\Column(length: 255)]
    private ?string $reader_name = null;

    /**
     * @var string|null Текст відгуку
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * @var int|null Оцінка
     */
    #[ORM\Column]
    private ?int $rating = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReaderName(): ?string
    {
        return $this->reader_name;
    }

    public function setReaderName(string $reader_name): static
    {
        $this->reader_name = $reader_name;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;
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
            'id'          => $this->id,
            'reader_name' => $this->reader_name,
            'content'     => $this->content,
            'rating'      => $this->rating,
        ];
    }
}