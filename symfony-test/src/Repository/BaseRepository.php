<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Базовий репозиторій для спільної логіки фільтрації та пагінації.
 * 
 * @template T of object
 * @extends ServiceEntityRepository<T>
 */
abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * Поля, які будуть шукатися за частковим збігом (LIKE).
     * Мають бути перевизначені в дочірніх репозиторіях.
     */
    public const LIKE_FIELDS = [];

    /**
     * Фільтрація та пагінація записів на основі HTTP запиту.
     *
     * @param Request $request
     * @param array $allowedParams Дозволені параметри для фільтрації
     * @return array
     * @throws \InvalidArgumentException
     */
    public function filterAndPaginate(Request $request, array $allowedParams = []): array
    {
        $qb = $this->createQueryBuilder('e');
        $queryParams = $request->query->all();

        foreach ($queryParams as $key => $value) {
            if (in_array($key, ['page', 'itemsPerPage'], true)) {
                continue;
            }

            $baseKey = preg_replace('/_(from|to)$/', '', $key);

            if (!in_array($baseKey, $allowedParams, true)) {
                throw new \InvalidArgumentException("Параметр '{$key}' не дозволений для фільтрації.");
            }

            if ($value !== null && $value !== '') {
                if (str_ends_with($key, '_from')) {
                    $qb->andWhere("e.$baseKey >= :$key")->setParameter($key, $value);
                } elseif (str_ends_with($key, '_to')) {
                    $qb->andWhere("e.$baseKey <= :$key")->setParameter($key, $value);
                } else {
                    if (in_array($key, static::LIKE_FIELDS, true)) {
                        $qb->andWhere("e.$key LIKE :$key")->setParameter($key, "%{$value}%");
                    } else {
                        $qb->andWhere("e.$key = :$key")->setParameter($key, $value);
                    }
                }
            }
        }

        $page = max(1, $request->query->getInt('page', 1));
        $perPage = max(1, min($request->query->getInt('itemsPerPage', 15), 100));
        $offset = ($page - 1) * $perPage;

        $qb->setFirstResult($offset)->setMaxResults($perPage);

        $paginator = new Paginator($qb);
        $totalItems = count($paginator);
        $lastPage = ceil($totalItems / $perPage);

        $data = [];
        foreach ($paginator as $item) {
            $data[] = $item;
        }

        return [
            'current_page' => $page,
            'data'         => $data,
            'per_page'     => $perPage,
            'total'        => $totalItems,
            'last_page'    => $lastPage,
        ];
    }
}