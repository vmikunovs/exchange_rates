<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

     /**
      * @return Currency[] Returns an array of Currency objects
      */
    public function findByExchange($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exchange = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByCodeAndExchangeId($code, $exchangeType): ?Currency
    {
        try {
            return $this->createQueryBuilder('c')
                ->andWhere('c.code = :code')
                ->andWhere('c.exchange = :exchangeType')
                ->setParameter('code', $code)
                ->setParameter('exchangeType', $exchangeType)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

}
