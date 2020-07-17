<?php


namespace App\GraphQL\Resolver;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Repository\AdvertisementRepository;
use App\Model\Flusher;
use Doctrine\ORM\EntityManager;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AdvertisementListSharedResolver
 * @package App\GraphQL\Resolver
 */
class AdvertisementListSharedResolver extends BaseResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var AdvertisementRepository
     */
    private AdvertisementRepository $advertisementRepo;

    /**
     * AdvertisementResolver constructor.
     * @param AdvertisementRepository $advertisementRepo
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(AdvertisementRepository $advertisementRepo, Flusher $flusher, TokenStorageInterface $tokenStorage)
    {
      parent::__construct($flusher, $tokenStorage);
      $this->advertisementRepo = $advertisementRepo;
    }

    /**
     * @param Argument $args
     * @return array
     */
    public function resolve(Argument $args)
    {
        try {
            $advertisements = $this->advertisementRepo->getAdvertisementList($args['category_id'] ?? null, $args['user_id'] ?? null, null,$args['page'] ?? 0, $args['sort_by_last_date'] ?? true, $args['sort_direction'] ?? 'desc') ?? [];
        } catch (\Exception $e) {
            throw GraphQLException::fromString('Ошибка при получениии списка объявлений');
        }
        return ['advertisements' => $advertisements];
    }

    public static function getAliases():array
    {
      return [
          'resolve' => 'AdvertisementListShared'
      ];
    }
}
