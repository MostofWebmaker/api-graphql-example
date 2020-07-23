<?php

declare(strict_types=1);

namespace App\GraphQL\Mutators;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Command\Advertisement\CreateAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Features\Advertisement\Advertisement\AdvertisementCreateFeature;
use App\Model\Flusher;
use App\Model\Advertisement\Form\Advertisement\CreateAdvertisementRequestFormType;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class AdvertisementCreateMutator extends BaseMutator implements MutationInterface
{
    /** @var FormFactoryInterface */
    protected FormFactoryInterface $formFactory;
    /**
     * @var AdvertisementCreateFeature
     */
    private AdvertisementCreateFeature $feature;

    /**
     * AdvertisementCreateMutator constructor.
     * @param FormFactoryInterface $formFactory
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     * @param AdvertisementCreateFeature $feature
     */
    public function __construct(
	    FormFactoryInterface $formFactory,
        Flusher $flusher,
        TokenStorageInterface $tokenStorage,
        AdvertisementCreateFeature $feature
    ) {
        parent::__construct($formFactory, $flusher, $tokenStorage);
        $this->formFactory = $formFactory;
        $this->feature = $feature;
    }

    /**
     * @param Argument $args
     * @return Advertisement|null
     * @throws GraphQLException
     */
    public function __invoke(Argument $args)
    {
	    $input = $args['input'] ?? [];
	    if (!$input) {
		    throw GraphQLException::fromString('Отсутствует тело запроса!');
	    }
        $request = new CreateAdvertisementRequestCommand();

        $form = $this->formFactory
            ->create(CreateAdvertisementRequestFormType::class, $request);

        $form->submit($input);

	    if (!($form->isSubmitted() && $form->isValid())) {

		    throw GraphQLException::fromFormErrors($form);
	    }

        try {
            $advertisement =  $this->feature->createAdvertisement($request, $this->user);
        } catch (\RuntimeException $exception) {
            throw GraphQLException::fromString($exception->getMessage());
        }
        return $advertisement ?? null;
    }
}