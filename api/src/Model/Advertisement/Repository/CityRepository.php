<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class CityRepository
 * @package App\Model\Advertisement\Repository
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @param string $cityName
     * @return City|null
     * @throws NonUniqueResultException
     */
    public function getCityByName(string $cityName): ?City
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.title = :title')
            ->setParameter('title', $cityName)
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
