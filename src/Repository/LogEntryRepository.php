<?php

namespace App\Repository;

use App\Entity\LogEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogEntry[]    findAll()
 */
class LogEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogEntry::class);
    }

    public function count(array $filters)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('COUNT(e.generatorId)');

        $this->applyFilters($filters, $qb);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findBy(array $filters, array $orderBy = null, $limit = null, $offset = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e');

        $this->applyFilters($filters, $qb);

        if (null !== $limit)
            $qb->setMaxResults($limit);

        if (null !== $offset)
            $qb->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    protected function applyFilters(array $filters, QueryBuilder $queryBuilder)
    {
        if (isset($filters['generatorId']))
        {
            if (is_array($filters['generatorId']))
                $queryBuilder->andWhere('e.generatorId IN (:generatorId)')
                    ->setParameter('generatorId', $filters['generatorId']);
            else
                $queryBuilder->andWhere('e.generatorId = :generatorId')
                    ->setParameter('generatorId', $filters['generatorId']);
        }

        if (isset($filters['dateFrom']))
            $queryBuilder->andWhere('e.measurementTime >= :dateFrom')
                ->setParameter('dateFrom', $filters['dateFrom']);

        if (isset($filters['dateTo']))
            $queryBuilder->andWhere('e.measurementTime <= :dateTo')
                ->setParameter('dateTo', $filters['dateTo']);
    }
}
