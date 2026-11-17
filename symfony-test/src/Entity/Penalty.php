<?php

namespace App\Entity;

use App\Repository\PenaltyRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Сутність Штрафу
 */
#[ORM\Entity(repositoryClass: PenaltyRepository::class)]
class Penalty implements JsonSerializable
{
    /**
     * @var int|null Унікальний ідентифікатор штрафу
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null Ім'я читача, який отримав штраф
     */
    #[ORM\Column(length: 255)]
    private ?string $reader_name = null;

    /**
     * @var float|null Сума штрафу
     */
    #[ORM\Column]
    private ?float $amount = null;

    /**
     * @var string|null Причина накладення штрафу
     */
    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    /**
     * @var bool|null Статус оплати штрафу (true - оплачено, false - ні)
     */
    #[ORM\Column(nullable: true)]
    private ?bool $is_paid = null;

    /**
     * Отримати ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Отримати ім'я читача
     * @return string|null
     */
    public function getReaderName(): ?string
    {
        return $this->reader_name;
    }

    /**
     * Встановити ім'я читача
     * @param string $reader_name
     * @return static
     */
    public function setReaderName(string $reader_name): static
    {
        $this->reader_name = $reader_name;
        return $this;
    }

    /**
     * Отримати суму штрафу
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * Встановити суму штрафу
     * @param float $amount
     * @return static
     */
    public function setAmount(float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Отримати причину штрафу
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * Встановити причину штрафу
     * @param string $reason
     * @return static
     */
    public function setReason(string $reason): static
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Перевірити статус оплати
     * @return bool|null
     */
    public function isPaid(): ?bool
    {
        return $this->is_paid;
    }

    /**
     * Встановити статус оплати
     * @param bool|null $is_paid
     * @return static
     */
    public function setIsPaid(?bool $is_paid): static
    {
        $this->is_paid = $is_paid;
        return $this;
    }

    /**
     * Серіалізація сутності в масив для JSON
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id'          => $this->id,
            'reader_name' => $this->reader_name,
            'amount'      => $this->amount,
            'reason'      => $this->reason,
            'is_paid'     => $this->is_paid,
        ];
    }
}