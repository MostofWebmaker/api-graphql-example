<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\AdvertisementStatusType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class AdvertisementStatusTypeRepository
 * @package App\Model\Advertisement\Repository
 */
class AdvertisementStatusTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvertisementStatusType::class);
    }

    /**
     * @param int $statusId
     * @return AdvertisementStatusType|null
     * @throws NonUniqueResultException
     */
    public function getAdvertisementStatusTypeByStatusId(int $statusId): ?AdvertisementStatusType
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.statusId = :statusId')
            ->setParameter('statusId', $statusId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
