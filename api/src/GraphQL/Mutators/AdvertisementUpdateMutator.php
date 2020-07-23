<?php

declare(strict_types=1);

namespace App\GraphQL\Mutators;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Command\Advertisement\CreateAdvertisementRequestCommand;
use App\Model\Advertisement\Command\Advertisement\UpdateAdvertisementRequestCommand;
use App\Model\Advertisement\Command\CategoryAdvertisement\UpdateCategoryAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Advertisement\Entity\AdvertisementStatus;
use App\Model\Advertisement\Form\Advertisement\UpdateAdvertisementRequestFormType;
use App\Model\Features\Advertisement\Advertisement\AdvertisementCreateFeature;
use App\Model\Features\Advertisement\Advertisement\AdvertisementUpdateFeature;
use App\Model\Flusher;
use App\Model\Advertisement\Form\Advertisement\CreateAdvertisementRequestFormType;
use App\Validator\Constraints\CustomCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validation;

class AdvertisementUpdateMutator extends BaseMutator implements MutationInterface
{
    /** @var FormFactoryInterface */
    protected FormFactoryInterface $formFactory;
    /**
     * @var AdvertisementUpdateFeature
     */
    private AdvertisementUpdateFeature $feature;

    /**
     * AdvertisementCreateMutator constructor.
     * @param FormFactoryInterface $formFactory
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     * @param AdvertisementUpdateFeature $feature
     */
    public function __construct(
	    FormFactoryInterface $formFactory,
        Flusher $flusher,
        TokenStorageInterface $tokenStorage,
        AdvertisementUpdateFeature $feature
    ) {
        parent::__construct($formFactory, $flusher, $tokenStorage);
        $this->formFactory = $formFactory;
        $this->feature = $feature;
    }

    /**
     * @param Argument $args
     * @return |null
     * @throws GraphQLException
     */
    public function __invoke(Argument $args)
    {
	    $input = $args['input'] ?? [];
	    if (!$input) {
		    throw GraphQLException::fromString('Отсутствует тело запроса!');
	    }

        $request = new UpdateAdvertisementRequestCommand();

        $form = $this->formFactory
            ->create(UpdateAdvertisementRequestFormType::class, $request);

        $form->submit($input);

	    if (!($form->isSubmitted() && $form->isValid())) {
		    throw GraphQLException::fromFormErrors($form);
	    }

        try {
            $advertisement = $this->feature->updateAdvertisement($request, $this->user);
        }  catch (\RuntimeException $exception) {
            throw GraphQLException::fromString($exception->getMessage());
        }
	    return  $advertisement ?? null;
    }
}