<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\District;
use App\Model\Advertisement\Entity\House;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class HouseRepository
 * @package App\Model\Advertisement\Repository
 */
class HouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, House::class);
    }

    /**
     * @param string $houseNumber
     * @return House|null
     * @throws NonUniqueResultException
     */
    public function getHouseByNumber(string $houseNumber): ?House
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.title = :title')
            ->setParameter('title', $houseNumber)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
