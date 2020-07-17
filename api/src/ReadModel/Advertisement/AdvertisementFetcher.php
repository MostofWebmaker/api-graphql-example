<?php

declare(strict_types=1);

namespace App\ReadModel\Advertisement;

use App\Model\Advertisement\Repository\AdvertisementRepository;
use App\Model\User\Entity\UserAccount;
use App\Model\User\Repository\UserRepository;
use App\ReadModel\Advertisement\Filter\AdvertisementFilter;
use App\ReadModel\NotFoundException;
use App\ReadModel\User\Filter\UserFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class AdvertisementFetcher
{
    /**
     * @var Connection
     */
    private Connection $connection;
    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;
    /**
     * @var AdvertisementRepository
     */
    private AdvertisementRepository $advertisementRepository;

    private UserRepository $userRepository;

    /**
     * AdvertisementFetcher constructor.
     * @param Connection $connection
     * @param UserRepository $userRepository
     * @param AdvertisementRepository $advertisementRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(Connection $connection, UserRepository $userRepository, AdvertisementRepository $advertisementRepository, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->advertisementRepository = $advertisementRepository;
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

//    public function existsByResetToken(string $token): bool
//    {
//        return $this->connection->createQueryBuilder()
//            ->select('COUNT (*)')
//            ->from('user_users')
//            ->where('reset_token_token = :token')
//            ->setParameter(':token', $token)
//            ->execute()->fetchColumn() > 0;
//    }
//
//    public function findForAuthByEmail(string $email): ?AuthView
//    {
//        $stmt = $this->connection->createQueryBuilder()
//            ->select(
//                'id',
//                'email',
//                'password_hash',
//                'TRIM(CONCAT(name_first, \' \', name_last)) AS name',
//                'role',
//                'status'
//            )
//            ->from('user_users')
//            ->where('email = :email')
//            ->setParameter(':email', $email)
//            ->execute();
//
//        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);
//        $result = $stmt->fetch();
//
//        return $result ?: null;
//    }
//
//    public function findForAuthByNetwork(string $network, string $identity): ?AuthView
//    {
//        $stmt = $this->connection->createQueryBuilder()
//            ->select(
//                'u.id',
//                'u.email',
//                'u.password_hash',
//                'TRIM(CONCAT(u.name_first, \' \', u.name_last)) AS name',
//                'u.role',
//                'u.status'
//            )
//            ->from('user_users', 'u')
//            ->innerJoin('u', 'user_user_networks', 'n', 'n.user_id = u.id')
//            ->where('n.network = :network AND n.identity = :identity')
//            ->setParameter(':network', $network)
//            ->setParameter(':identity', $identity)
//            ->execute();
//
//        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);
//        $result = $stmt->fetch();
//
//        return $result ?: null;
//    }
//
//    public function findByEmail(string $email): ?ShortView
//    {
//        $stmt = $this->connection->createQueryBuilder()
//            ->select(
//                'id',
//                'email',
//                'role',
//                'status'
//            )
//            ->from('user_users')
//            ->where('email = :email')
//            ->setParameter(':email', $email)
//            ->execute();
//
//        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, ShortView::class);
//        $result = $stmt->fetch();
//
//        return $result ?: null;
//    }
//
////	public function findByResetToken(string $token): UserAccount
////	{
////		$stmt =  $this->connection->createQueryBuilder()
////			->select('*')
////			->from('user_accounts')
////			->where('reset_token_token = :token')
////			->setParameter(':token', $token)
////			->execute();
////
////
////		$stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, UserAccount::class);
////		$result = $stmt->fetch();
////		return $result ?: null;
////	}
//
//    public function findBySignUpConfirmToken(string $token): ?ShortView
//    {
//        $stmt = $this->connection->createQueryBuilder()
//            ->select(
//                'id',
//                'email',
//                'role',
//                'status'
//            )
//            ->from('user_users')
//            ->where('confirm_token = :token')
//            ->setParameter(':token', $token)
//            ->execute();
//
//        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, ShortView::class);
//        $result = $stmt->fetch();
//
//        return $result ?: null;
//    }
//
//    public function get(int $id): object
//    {
//        if (!$user = $this->userRepository->find($id)) {
//            throw new NotFoundException('User is not found');
//        }
//        return $user;
//    }

    /**
     * @param AdvertisementFilter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(AdvertisementFilter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'a.id',
                'ba.title as title',
                'TRIM(CONCAT(name_last, \' \', name_first, \' \', name_middle)) AS userFio',
                'a.date_created',
                'a.date_updated',
                'c.title as country',
                'ci.title as city',
                'di.title as district',
                'adst.description as status',
                'u.id as userId'
            )
            ->from('advertisements','a')
            ->leftJoin('a', 'body_advertisements', 'ba', 'a.body_advertisement_id = ba.id')
            ->leftJoin('a', 'addresses', 'adr', 'a.address_id = adr.id')
            ->leftJoin('a', 'users', 'u', 'a.user_id = u.id')
            ->leftJoin('a', 'advertisement_statuses', 'ads', 'a.status_id = ads.id')
            ->leftJoin('adr', 'countries', 'c', 'adr.country = c.id')
            ->leftJoin('adr', 'cities', 'ci', 'adr.city = ci.id')
            ->leftJoin('adr', 'districts', 'di', 'adr.district = di.id')
            ->leftJoin('ads', 'advertisement_status_types', 'adst', 'ads.advertisement_status_type_id = adst.id')
            ->where('u.roles = JSON_ARRAY("ROLE_USER")');


        if ($filter->userFio) {
            $qb->andWhere($qb->expr()->like('LOWER(CONCAT(name_last, \' \', name_first,\' \', name_middle))', ':userFio'));
            $qb->setParameter(':userFio', '%' . mb_strtolower($filter->userFio) . '%');
        }

        if ($filter->title) {
            $qb->andWhere($qb->expr()->like('LOWER(ba.title)', ':title'));
            $qb->setParameter(':title', '%' . mb_strtolower($filter->title) . '%');
        }
//
//        if ($filter->phone) {
//            $qb->andWhere('phone = :phone');
//            $qb->setParameter(':phone', $filter->phone);
//        }

        if ($filter->status) {
            $qb->andWhere('adst.description = :status');
            $qb->setParameter(':status', $filter->status);
        }

//        if ($filter->status) {
//            $qb->andWhere('status = :status');
//            $qb->setParameter(':status', $filter->status);
//        }

        if (!\in_array($sort, ['id','title','status', 'date_created', 'date_updated', 'user', 'userFio','country', 'city', 'district'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'asc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }
}
