<?php


namespace App\GraphQL\Resolver;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Repository\CategoryAdvertisementRepository;
use App\Model\Flusher;
use App\Model\Notifications\Repository\NotificationsRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class NotificationListResolver
 * @package App\GraphQL\Resolver
 */
class NotificationListResolver extends BaseResolver implements ResolverInterface, AliasedInterface
{
    /** @var NotificationsRepository $notificationsRepository */
    private NotificationsRepository $notificationsRepository;

    /** @var Security $security */
    private Security $security;

    /**
     * CategoryAdvertisementResolver constructor.
     * @param NotificationsRepository $notificationsRepository
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     * @param Security $security
     */
    public function __construct(NotificationsRepository $notificationsRepository, Flusher $flusher, TokenStorageInterface $tokenStorage, Security $security)
    {
      parent::__construct($flusher, $tokenStorage);
      $this->notificationsRepository = $notificationsRepository;
      $this->security = $security;
    }

    /**
     * @param Argument $args
     * @return array[]
     * @throws GraphQLException
     */
    public function resolve(Argument $args)
    {
        try {
            if ($eventTypeId = $args['event_type']) {
                $notifications = $this->notificationsRepository->getNotificationsByUserIdAndEventTypeId(
                        $this->user->getId(),
                        $eventTypeId,
                        $args['limit'] ?? 100
                    ) ?? [];
            } else {
                $notifications = $this->notificationsRepository->getNotificationsByUserId(
                        $this->user->getId(),
                        $args['limit'] ?? 100
                    ) ?? [];
                dump($notifications);
            }
        } catch (\RuntimeException $e) {
            throw GraphQLException::fromString($e->getMessage());
        }
        return ['notifications' => $notifications ?? []];
    }

    public static function getAliases(): array
    {
      return [
          'resolve' => 'NotificationList'
      ];
    }
}
