<?php


namespace App\GraphQL\Resolver;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Advertisement\Repository\AdvertisementRepository;
use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\User\Repository\UserAdvertisementRepository;
use App\Model\User\Repository\UserRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\QueryException;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class AdvertisementListResolver
 * @package App\GraphQL\Resolver
 */
class AdvertisementListResolver extends BaseResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var AdvertisementRepository
     */
    private AdvertisementRepository $advertisementRepo;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserAdvertisementRepository
     */
    private UserAdvertisementRepository $userAdvertisementRepository;

    /**
     * @var Security
     */
    private Security $security;

    /**
     * AdvertisementListResolver constructor.
     * @param AdvertisementRepository $advertisementRepo
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     * @param Security $security
     * @param UserRepository $userRepository
     * @param UserAdvertisementRepository $userAdvertisementRepository
     */
    public function __construct(AdvertisementRepository $advertisementRepo, Flusher $flusher, TokenStorageInterface $tokenStorage, Security $security, UserRepository $userRepository, UserAdvertisementRepository $userAdvertisementRepository)
    {
      parent::__construct($flusher, $tokenStorage);
      $this->advertisementRepo = $advertisementRepo;
      $this->security = $security;
      $this->userRepository = $userRepository;
      $this->userAdvertisementRepository = $userAdvertisementRepository;
    }

    /**
     * @param Argument $args
     * @return \App\Model\Advertisement\Entity\Advertisement[][]
     * @throws DBALException
     * @throws QueryException
     */
    public function resolve(Argument $args)
    {

        try {
            /**
             * @var User $user
             */
            if ($args['user_id'] && !($user= $this->userRepository->find($args['user_id']))) {
                throw new \RuntimeException("Пользователь с id #{$args['user_id']} не найден!");
            }

            $isAdmin = false;
            //полный список объявлений пользователя видит только сам пользователь и админ
            if ($this->security->isGranted('ROLE_ADMIN') && !in_array(User::ROLE_ADMIN, $this->user->getRoles())) {
                //throw GraphQLException::fromString('Доступ запрещен. Менять статус пользователя разрешено только администратору!');
                $isAdmin = true;
            } else if ($args['user_id'] && ($user->getUsername() !== $this->user->getUsername())) {
                throw new \RuntimeException("Доступ запрещен. Полный список объявлений пользователя видит только сам пользователь!");
            }
            /**
             * @var Advertisement[] $advertisements
             */
            $advertisements = $this->advertisementRepo->getAdvertisementList($args['category_id'] ?? null, $args['user_id'] ?? null, null,$args['page'] ?? 0, $args['sort_by_last_date'] ?? true, $args['sort_direction'] ?? 'desc', $isAdmin) ?? [];
            $currentUser = ($args['user_id'] && $user) ? $user : $this->userRepository->getByUUID($this->user->getUsername());
            if (!$currentUser) {
                throw new \RuntimeException("Не найден текущий пользователь с id #{$this->user->getUsername()}!");
            }
            if ($advertisements && ($items = $this->userAdvertisementRepository->getFavoritesAdvertisementIdsByUserId($currentUser->getId()))) {
                    foreach ($advertisements as $advertisement) {
                        if (in_array($advertisement->getId(), $items)) {
                            $advertisement->setIsFavorite(true);
                        }
                    }
            }
        } catch (\RuntimeException $e) {
            throw GraphQLException::fromString($e->getMessage());
        }
        return ['advertisements' => $advertisements];
    }

    public static function getAliases():array
    {
      return [
          'resolve' => 'AdvertisementList'
      ];
    }
}
