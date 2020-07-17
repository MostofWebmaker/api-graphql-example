<?php

declare(strict_types=1);

namespace App\GraphQL\Mutators;

use App\Exceptions\GraphQLException;
use App\Model\Advertisement\Command\Advertisement\AdvertisementStatusChangeRequestCommand;
use App\Model\Advertisement\Command\Advertisement\CreateAdvertisementRequestCommand;
use App\Model\Advertisement\Command\Advertisement\UpdateAdvertisementRequestCommand;
use App\Model\Advertisement\Command\CategoryAdvertisement\UpdateCategoryAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Advertisement\Entity\AdvertisementStatus;
use App\Model\Advertisement\Form\Advertisement\AdvertisementStatusChangeRequestFormType;
use App\Model\Advertisement\Form\Advertisement\UpdateAdvertisementRequestFormType;
use App\Model\Features\Advertisement\Advertisement\AdvertisementCreateFeature;
use App\Model\Features\Advertisement\Advertisement\AdvertisementStatusChangeFeature;
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

class AdvertisementStatusChangeMutator extends BaseMutator implements MutationInterface
{
    /** @var FormFactoryInterface */
    protected FormFactoryInterface $formFactory;
    /**
     * @var AdvertisementStatusChangeFeature
     */
    private AdvertisementStatusChangeFeature $feature;

    /**
     * AdvertisementStatusChangeMutator constructor.
     * @param FormFactoryInterface $formFactory
     * @param Flusher $flusher
     * @param TokenStorageInterface $tokenStorage
     * @param AdvertisementStatusChangeFeature $feature
     */
    public function __construct(
	    FormFactoryInterface $formFactory,
        Flusher $flusher,
        TokenStorageInterface $tokenStorage,
        AdvertisementStatusChangeFeature $feature
    ) {
        parent::__construct($formFactory, $flusher, $tokenStorage);
        $this->formFactory = $formFactory;
        $this->feature = $feature;
    }

    /**
     * @param Argument $args
     * @return AdvertisementStatus|null
     */
    public function __invoke(Argument $args)
    {
	    $input = $args['input'] ?? [];
	    if (!$input) {
		    throw GraphQLException::fromString('Отсутствует тело запроса!');
		}

        $request = new AdvertisementStatusChangeRequestCommand();

        $form = $this->formFactory
            ->create(AdvertisementStatusChangeRequestFormType::class, $request);

        $form->submit($input);

	    if (!($form->isSubmitted() && $form->isValid())) {
		    throw GraphQLException::fromFormErrors($form);
	    }

        try {
            $advertismentStatus = $this->feature->changeStatus($request, $this->user);
        } catch (\RuntimeException $exception) {
            throw GraphQLException::fromString($exception->getMessage());
        }
	    return $advertismentStatus ?? null;
    }
}





/*$csrfProvider = new SessionCsrfProvider($session, $csrfSecret);

$formFactory = Forms::createFormFactoryBuilder()
	// ...
	->addExtension(new CsrfExtension($csrfProvider))
	->getFormFactory();*/