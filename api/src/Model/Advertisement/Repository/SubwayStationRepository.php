<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\SubwayStation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class SubwayStationRepository
 * @package App\Model\Advertisement\Repository
 */
class SubwayStationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubwayStation::class);
    }

    /**
     * @param string $subwayStationName
     * @return SubwayStation|null
     * @throws NonUniqueResultException
     */
    public function getSubwayStationByName(string $subwayStationName): ?SubwayStation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.title = :title')
            ->setParameter('title', $subwayStationName)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
