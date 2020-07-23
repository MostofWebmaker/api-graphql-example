<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\BodyAdvertisement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class BodyAdvertisementRepository
 * @package App\Model\Advertisment\Repository
 */
class BodyAdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BodyAdvertisement::class);
    }
}
