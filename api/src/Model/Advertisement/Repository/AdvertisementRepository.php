<?php

namespace App\Model\Advertisement\Repository;

use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Advertisement\Entity\AdvertisementStatusType;
use App\Model\Advertisement\Entity\CategoryAdvertisement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\QueryException;

/**
 * Class AdvertisementRepository
 * @package App\Model\Advertisment\Repository
 */
class AdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }


    /**
     * @param int|null $category_id
     * @param int|null $user_id
     * @param int|null $limit
     * @param int|null $page
     * @param bool $sort_by_last_date
     * @param string $sortingDirection
     * @param bool $isAdmin
     * @return array
     * @throws QueryException
     */
    public function getAdvertisementList(?int $category_id = null, ?int $user_id = null, ?int $limit = 100, ?int $page = 0, bool $sort_by_last_date = true, string  $sortingDirection = 'desc', bool $isAdmin = false): array
    {
        if ($isAdmin) {
            $advertisementsIds = [];
        } else if ($user_id) {
            $criteria['user'] = $user_id;
            $advertisementsIds = $this->getAdvertisementIdsByUserId($user_id);
        } else if (!$advertisementsIds = $this->getActiveAdvertisementIds()) {
            return [];
        }
        $criteria = [];
        $criteria['id'] = $advertisementsIds;
        $orderBy = [];
        if ($sort_by_last_date) {
            $orderBy = ['dateUpdated' => $sortingDirection];
            $sortingDirection = 'desc';
        }
        if ($category_id) {
            $criteria['categoryAdvertisement'] = $category_id;
        }
        $advertisements = $this->findBy($criteria, $orderBy, $limit, $limit * $page);
        return $advertisements ?? [];
    }

    /**
     * @param int $userId
     * @return array|null
     */
    public function getAdvertisementsByUserId(int $userId): ?array
    {
        $result =  $this->createQueryBuilder('a')
            ->andWhere('a.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getArrayResult()
            ;
        return $result ?? [];
    }

    /**
     * @param int $userId
     * @return array|null
     * @throws QueryException
     */
    public function getAdvertisementIdsByUserId(int $userId): ?array
    {
        $result =  $this->createQueryBuilder('a')
            ->select('a.id')
            ->where('a.user = :userId')
            ->setParameter('userId', $userId)
            ->indexBy('a', 'a.id')
            ->getQuery()
            ->getArrayResult()
            ;
        return $result ? array_keys($result) : [];
    }

    /**
     * @return array|int|string
     */
    public function getActiveAdvertisementIds() {
        $advertisementsIds =  $this->createQueryBuilder('a')
            ->select('a.id')
            ->innerJoin('a.status', 's')
            ->where('s.advertisementStatusType = :advertisementStatusMain')
            ->orWhere('s.advertisementStatusType = :advertisementStatusPremium')
            ->setParameter('advertisementStatusMain', AdvertisementStatusType::STATUS_ACTIVE)
            ->setParameter('advertisementStatusPremium', AdvertisementStatusType::STATUS_PREMIUM_ACTIVE)
            ->indexBy('a', 'a.id')
            ->getQuery()
            ->getArrayResult()
        ;
        return $advertisementsIds ? array_keys($advertisementsIds) : [];
    }
}
