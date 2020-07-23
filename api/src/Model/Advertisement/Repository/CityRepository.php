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
}
