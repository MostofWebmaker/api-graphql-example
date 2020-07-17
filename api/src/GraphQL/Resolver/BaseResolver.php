<?php


namespace App\GraphQL\Resolver;

use App\Model\Flusher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class BaseResolver
 * @package App\GraphQL\Resolver
 */
abstract class BaseResolver{

    protected Flusher $flusher;
    protected $user;

    public function __construct(Flusher $flusher, TokenStorageInterface $tokenStorage)
    {
        $this->flusher = $flusher;
        $this->user = $tokenStorage->getToken() ? $tokenStorage->getToken()->getUser() : null;
    }
}
