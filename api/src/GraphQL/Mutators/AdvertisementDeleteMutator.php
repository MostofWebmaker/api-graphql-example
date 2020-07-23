<?php

declare(strict_types=1);

namespace App\GraphQL\Mutators;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Command\Advertisement\DeleteAdvertisementRequestCommand;
use App\Model\Advertisement\Form\Advertisement\DeleteAdvertisementRequestFormType;
use App\Model\Features\Advertisement\Advertisement\AdvertisementDeleteFeature;
use App\Model\Flusher;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class AdvertisementDeleteMutator extends BaseMutator implements MutationInterface
{
    /** @var FormFactoryInterface */
    protected FormFactoryInterface $formFactory;
    /**
     * @var AdvertisementDeleteFeature
     */
    private AdvertisementDeleteFeature $feature;

    /**
     * AdvertisementDeleteMutator constructor.
     * @param FormFactoryInterface $formFactory
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     * @param AdvertisementDeleteFeature $feature
     */
    public function __construct(
	    FormFactoryInterface $formFactory,
        Flusher $flusher,
        TokenStorageInterface $tokenStorage,
        AdvertisementDeleteFeature $feature
    ) {
        parent::__construct($formFactory, $flusher, $tokenStorage);
        $this->formFactory = $formFactory;
        $this->feature = $feature;
    }

    /**
     * @param Argument $args
     * @return bool[]
     * @throws GraphQLException
     */
    public function __invoke(Argument $args)
    {
	    $input = $args['input'] ?? [];
	    if (!$input) {
		    throw GraphQLException::fromString('Отсутствует тело запроса!');
	    }

        $request = new DeleteAdvertisementRequestCommand();
        $form = $this->formFactory
            ->create(DeleteAdvertisementRequestFormType::class, $request);

        $form->submit($input);

	    if (!($form->isSubmitted() && $form->isValid())) {
		    throw GraphQLException::fromFormErrors($form);
	    }

        try {
            $this->feature->deleteAdvertisement($request, $this->user);
        } catch (\RuntimeException $exception) {
            throw GraphQLException::fromString($exception->getMessage());
        }
        return ['result' => true];
    }
}