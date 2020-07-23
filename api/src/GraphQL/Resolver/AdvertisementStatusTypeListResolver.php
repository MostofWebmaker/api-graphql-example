<?php

namespace App\GraphQL\Resolver;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Entity\AdvertisementStatusType;
use App\Model\Advertisement\Repository\AdvertisementStatusTypeRepository;
use App\Model\Flusher;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class AdvertisementStatusTypeListResolver
 * @package App\GraphQL\Resolver
 */
class AdvertisementStatusTypeListResolver extends BaseResolver implements ResolverInterface, AliasedInterface
{
    /** @var Security $security */
    private Security $security;
    /**
     * @var AdvertisementStatusTypeRepository
     */
    private AdvertisementStatusTypeRepository $advertisementStatusTypeRepository;

    /**
     * AdvertisementStatusTypeListResolver constructor.
     * @param AdvertisementStatusTypeRepository $advertisementStatusTypeRepository
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     * @param Security $security
     */
    public function __construct(AdvertisementStatusTypeRepository $advertisementStatusTypeRepository, Flusher $flusher, TokenStorageInterface $tokenStorage, Security $security)
    {
      parent::__construct($flusher, $tokenStorage);
      $this->advertisementStatusTypeRepository = $advertisementStatusTypeRepository;
      $this->security = $security;
    }

    /**
     * @param Argument $args
     * @return \App\Model\Advertisement\Entity\AdvertisementStatusType[][]|array[]
     * @throws GraphQLException
     */
    public function resolve(Argument $args)
    {
        try {
            /**
             * @var  AdvertisementStatusType[] $items
             */
            $items = $this->advertisementStatusTypeRepository->findAll();
            if (!$this->security->isGranted('ROLE_ADMIN', $this->user)) {
                $userStatusesListIds = array_keys(AdvertisementStatusType::ADVERTISEMENT_USER_STATUS_LIST);
                $filteredItems = [];
                foreach ($items as $item) {
                    if (in_array($item->getId(), $userStatusesListIds, true)) {
                        $filteredItems[] = $item;
                    }
                }
                $advertisementStatusTypes = $filteredItems;
            } else {
                $advertisementStatusTypes = $items;
            }
        } catch (\RuntimeException $e) {
            throw GraphQLException::fromString($e->getMessage());
        }
        return ['advertisementStatusType' => $advertisementStatusTypes ];
    }

    public static function getAliases():array
    {
      return [
          'resolve' => 'AdvertisementStatusTypeList'
      ];
    }
}
