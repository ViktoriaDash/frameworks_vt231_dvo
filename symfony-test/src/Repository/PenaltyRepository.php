<?php

namespace App\Repository;

use App\Entity\Penalty;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<Penalty>
 */
class PenaltyRepository extends BaseRepository
{
    // Поля для пошуку за частковим збігом (LIKE)
    public const LIKE_FIELDS = ['reader_name', 'reason'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Penalty::class);
    }
}