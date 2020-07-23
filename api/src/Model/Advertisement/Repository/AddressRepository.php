<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class AddressRepository
 * @package App\Model\Advertisement\Repository
 */
class AddressRepository extends ServiceEntityRepository
{
    /**
     * AddressRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    /**
     * @param string $county
     * @param string $city
     * @param string $district
     * @param string|null $street
     * @param string|null $house
     * @return Address|null
     */
    public function getIdentityAddress(string $county, string $city, string $district, ?string $street = null, ?string $house = null): ?Address
    {
        $result =  $this->createQueryBuilder('a')
            ->where('a.country = :county')
            ->andWhere('a.city = :city')
            ->andWhere('a.district = :district')
            ->andWhere('a.street = :street')
            ->andWhere('a.house = :house')
            ->setParameter('county', $county)
            ->setParameter('city', $city)
            ->setParameter('district', $district)
            ->setParameter('street', $street)
            ->setParameter('house', $house)
            ->getQuery()
            ->getOneOrNullResult();
        return $result ?? null;
    }
}
