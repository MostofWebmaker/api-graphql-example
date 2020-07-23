<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\Country;
use App\Model\EntityNotFoundException;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class CountryRepository
 * @package App\Model\Advertisement\Repository
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @param string $countryName
     * @return Country|null
     * @throws NonUniqueResultException
     */
    public function getCountryByName(string $countryName): ?Country
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.title = :title')
            ->setParameter('title', $countryName)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
