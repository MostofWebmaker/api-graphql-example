<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\Street;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class StreetRepository
 * @package App\Model\Advertisement\Repository
 */
class StreetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Street::class);
    }

    /**
     * @param string $streetName
     * @return Street|null
     * @throws NonUniqueResultException
     */
    public function getStreetByName(string $streetName): ?Street
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.title = :title')
            ->setParameter('title', $streetName)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Advertisement[] Returns an array of Advertisement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Advertisement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
