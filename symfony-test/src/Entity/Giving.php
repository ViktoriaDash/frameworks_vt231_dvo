<?php

namespace App\Entity;

use App\Repository\GivingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GivingRepository::class)]
class Giving
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;
    #[ORM\ManyToOne(targetEntity: Book::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;
    #[ORM\ManyToOne(targetEntity: Reader::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reader $reader = null;
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $givingDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }


    public function getReader(): ?Reader
    {
        return $this->reader;
    }

    public function setReader(?Reader $reader): static
    {
        $this->reader = $reader;

        return $this;
    }

    public function getGivingDate(): ?\DateTimeInterface
    {
        return $this->givingDate;
    }

    public function setGivingDate(\DateTimeInterface $givingDate): static
    {
        $this->givingDate = $givingDate;

        return $this;
    }
}