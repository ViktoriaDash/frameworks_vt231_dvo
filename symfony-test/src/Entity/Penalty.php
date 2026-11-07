<?php

namespace App\Entity;

use App\Repository\PenaltyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PenaltyRepository::class)]
class Penalty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reader_name = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_paid = null;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

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

    public function isPaid(): ?bool
    {
        return $this->is_paid;
    }

    public function setIsPaid(?bool $is_paid): static
    {
        $this->is_paid = $is_paid;

        return $this;
    }
}
