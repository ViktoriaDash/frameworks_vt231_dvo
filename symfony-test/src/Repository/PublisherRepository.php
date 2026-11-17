<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<Publisher>
 */
class PublisherRepository extends BaseRepository
{
    // Поля для пошуку за частковим збігом (LIKE)
    public const LIKE_FIELDS = ['name', 'address', 'website'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }
}