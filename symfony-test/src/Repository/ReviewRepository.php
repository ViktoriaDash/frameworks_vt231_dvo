<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<Review>
 */
class ReviewRepository extends BaseRepository
{
    // Поля для пошуку за частковим збігом (LIKE)
    public const LIKE_FIELDS = ['reader_name', 'content'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }
}