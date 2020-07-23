<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\AdvertisementStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class AdvertisementStatusRepository
 * @package App\Model\Advertisment\Repository
 */
class AdvertisementStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvertisementStatus::class);
    }
}
