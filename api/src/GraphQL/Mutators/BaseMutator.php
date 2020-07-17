<?php


namespace App\GraphQL\Mutators;

use App\Model\Flusher;
use App\Model\User\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class BaseMutator
 * @package App\GraphQL\Mutators
 */
class BaseMutator
{
    /**
     * @var Flusher
     */
	protected Flusher $flusher;

    /** @var FormFactoryInterface */
	protected FormFactoryInterface $formFactory;

    /** @var User|null */
    protected $user;

    /**
     * BaseMutator constructor.
     * @param FormFactoryInterface $formFactory
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
      FormFactoryInterface $formFactory,
      Flusher $flusher,
      TokenStorageInterface $tokenStorage
  ) {
        $this->formFactory = $formFactory;
        $this->flusher = $flusher;
        $this->user = ($tokenStorage->getToken() && ($tokenStorage->getToken()->getUser() instanceof User)) ? $tokenStorage->getToken()->getUser() : null;
    }
}
