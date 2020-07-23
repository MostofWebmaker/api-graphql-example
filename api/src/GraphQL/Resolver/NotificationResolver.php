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
 * Class CategoryAdvertisementResolver
 * @package App\GraphQL\Resolver
 */
class NotificationResolver extends BaseResolver implements ResolverInterface, AliasedInterface
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
     * @return |null
     * @throws GraphQLException
     */
    public function resolve(Argument $args)
    {
        if ($notificationId = $args['id']) {
            try {
                if (!$notification = $this->notificationsRepository->find($notificationId)) {
                    throw new \RuntimeException("Уведомления с id #{$notificationId} не существует!");
                }
            } catch (\RuntimeException $e) {
                throw GraphQLException::fromString($e->getMessage());
            }
        }
        return $notification ?? null;
    }

    public static function getAliases(): array
    {
      return [
          'resolve' => 'Notification'
      ];
    }
}
