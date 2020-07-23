<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class DistrictRepository
 * @package App\Model\Advertisement\Repository
 */
class DistrictRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, District::class);
    }

    /**
     * @param string $districtName
     * @return District|null
     * @throws NonUniqueResultException
     */
    public function getDistrictByName(string $districtName): ?District
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.title = :title')
            ->setParameter('title', $districtName)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
