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
 * Class AdvertisementResolver
 * @package App\GraphQL\Resolver
 */
class AdvertisementResolver extends BaseResolver implements ResolverInterface, AliasedInterface
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
     * @return |null
     * @throws GraphQLException
     */
    public function resolve(Argument $args)
    {
        if ((int)$args['id']) {
            try {
                $advertisement = $this->advertisementRepo->find($args['id']);
            } catch (\Exception $e) {
                throw GraphQLException::fromString("Объявления с #id {$args['id']} не существует!");
            }
        }
        return $advertisement ?? null;
    }

    public static function getAliases():array
    {
      return [
          'resolve' => 'Advertisement'
      ];
    }
}
