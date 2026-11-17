<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends BaseRepository<Employee>
 */
class EmployeeRepository extends BaseRepository
{
    public const LIKE_FIELDS = ['name', 'position', 'phone'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }
}