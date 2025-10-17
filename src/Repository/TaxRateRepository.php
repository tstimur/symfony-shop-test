<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TaxRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaxRate>
 */
class TaxRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxRate::class);
    }

    /*
     * Поиск налоговой ставки для страны на конкретную дату
     */
    public function findByCountryCodeAndDate(string $countryCode, \DateTime $date): ?TaxRate
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.countryCode = :countryCode')
            ->andWhere('t.effectiveFrom <= :date')
            ->andWhere('t.effectiveUntil IS NULL OR t.effectiveUntil > :date')
            ->setParameter('countryCode', $countryCode)
            ->setParameter('date', $date)
            ->orderBy('t.effectiveFrom', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
