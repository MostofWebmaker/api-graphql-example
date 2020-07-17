<?php

namespace App\Model\Notifications\Repository;

use App\Model\Notifications\Entity\Notifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class NotificationsRepository
 * @package App\Model\Notifications\Repository
 */
class NotificationsRepository extends ServiceEntityRepository
{
    /**
     * NotificationsRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notifications::class);
    }

    public function getNotificationsByUserId(int $userId, int $limit = 100): ?array
    {
        $notifications =  $this->createQueryBuilder('n')
            ->where('n.user = :user_id')
            ->orderBy('n.dateCreated', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult()
            ;
        return  $notifications ?? [];
    }

    public function getNotificationsByUserIdAndEventTypeId(int $userId, int $eventTypeId, int $limit = 100): ?array
    {
        $notifications =  $this->createQueryBuilder('n')
            ->where('n.user = :user_id')
            ->andWhere('n.eventType = :eventTypeId')
            ->orderBy('n.dateCreated', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('user_id', $userId)
            ->setParameter('eventTypeId', $eventTypeId)
            ->getQuery()
            ->getResult()
            ;
        return  $notifications ?? [];
    }
}
