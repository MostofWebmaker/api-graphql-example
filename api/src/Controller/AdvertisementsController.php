<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Advertisement\Command\Advertisement\AdvertisementStatusChangeRequestCommand;
use App\Model\Advertisement\Entity\Advertisement;
use App\Model\Advertisement\Repository\AdvertisementRepository;
use App\Model\Advertisement\Repository\AdvertisementStatusTypeRepository;
use App\Model\Features\Advertisement\Advertisement\AdvertisementStatusChangeByAdminFeature;
use App\Model\User\Entity\Sex;
use App\Model\User\Entity\User;
use App\ReadModel\Advertisement\AdvertisementFetcher;
use App\ReadModel\Advertisement\ChangeAdvertisementStatus\ChangeAdvertisementStatusAdminCommand;
use App\ReadModel\Advertisement\ChangeAdvertisementStatus\ChangeAdvertisementStatusAdminForm;
use App\ReadModel\Advertisement\Filter\AdvertisementFilter;
use App\ReadModel\Advertisement\Filter\AdvertisementForm;
use App\Model\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/advertisements", name="advertisements")
 * @IsGranted("ROLE_ADMIN")
 */
class AdvertisementsController extends AbstractController
{
    //@IsGranted("ROLE_ADMIN")
    private const PER_PAGE = 50;

    private ErrorHandler $errors;

    /** @var FormFactoryInterface */
    protected FormFactoryInterface $formFactory;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var AdvertisementRepository
     */
    private AdvertisementRepository $advertisementRepository;
    /**
     * @var AdvertisementStatusTypeRepository
     */
    private AdvertisementStatusTypeRepository $advertisementStatusTypeRepository;
    /**
     * @var AdvertisementStatusChangeByAdminFeature
     */
    private AdvertisementStatusChangeByAdminFeature $advertisementStatusChangeByAdminFeature;

    /**
     * AdvertisementsController constructor.
     * @param ErrorHandler $errors
     * @param FormFactoryInterface $formFactory
     * @param UserRepository $userRepository
     * @param AdvertisementRepository $advertisementRepository
     * @param AdvertisementStatusTypeRepository $advertisementStatusTypeRepository
     * @param AdvertisementStatusChangeByAdminFeature $advertisementStatusChangeByAdminFeature
     */
    public function __construct(
        ErrorHandler $errors,
        FormFactoryInterface $formFactory,
        UserRepository $userRepository,
        AdvertisementRepository $advertisementRepository,
        AdvertisementStatusTypeRepository $advertisementStatusTypeRepository,
        AdvertisementStatusChangeByAdminFeature $advertisementStatusChangeByAdminFeature
    ) {
        $this->errors = $errors;
        $this->formFactory = $formFactory;
        $this->userRepository = $userRepository;
        $this->advertisementRepository = $advertisementRepository;
        $this->advertisementStatusTypeRepository = $advertisementStatusTypeRepository;
        $this->advertisementStatusChangeByAdminFeature = $advertisementStatusChangeByAdminFeature;
    }

    /**
     * @Route("", name="")
     * @param Request $request
     * @param AdvertisementFetcher $fetcher
     * @return Response
     */
    public function index(Request $request, AdvertisementFetcher $fetcher): Response
    {
        $filter = new AdvertisementFilter();

        $form = $this->createForm(AdvertisementForm::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'date_created'),
            $request->query->get('direction', 'desc'),
        );

        return $this->render('app/advertisements/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name=".edit")
     * @param Request $request
     * @param ChangeAdvertisementStatusAdminCommand $command
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function edit(Request $request, ChangeAdvertisementStatusAdminCommand $command): Response
    {
        /**
         * @var Advertisement $advertisement
         */
        if (!$advertisement = $this->advertisementRepository->find($request->attributes->get('id'))) {
            throw new \RuntimeException("Объявление с id  #{$request->attributes->get('id')} не найдено!");
        }

        /**
         * @var User $user
         */
        if (!$user = $advertisement->getUser()) {
            throw new \RuntimeException("Пользователь для объявления с id  #{$request->attributes->get('id')} не найден!");
        }

        if (!$statusDescription = $this->advertisementStatusTypeRepository->find($advertisement->getStatus()->getAdvertisementStatusType())) {
            throw new \RuntimeException("Статус пользователя с id  #{$user->getId()} не найден!");
        }
        $message = $advertisement->getStatus()->getMessage() ?? 'нет сообщения';

        $form = $this->createForm(ChangeAdvertisementStatusAdminForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $featureCommand = new AdvertisementStatusChangeRequestCommand();
                $featureCommand->setUserId($user->getId());
                $featureCommand->setStatusId($form->getViewData()->getStatusId());
                $featureCommand->setAdvertisementId($advertisement->getId());
                if ($message = $form->getViewData()->getMessage()) {
                    $featureCommand->setMessage($message);
                }
                $this->advertisementStatusChangeByAdminFeature->changeStatusByAdmin($featureCommand, $user);
                return $this->redirectToRoute('advertisements.show', ['userId' => $user->getId(), 'id' => $advertisement->getId()]);
            } catch (\RuntimeException $e) {
                $this->addFlash('error', $e->getMessage());
                $this->errors->handle($e);
                throw new \RuntimeException($e->getMessage());
            }
        }
        return $this->render('app/advertisements/edit.html.twig', [
            'user' => $user,
            'message' => $message,
            'advertisement' => $advertisement,
            'form' => $form->createView(),
            'statusDescription' => $statusDescription
        ]);
    }

    /**
     * @Route("/{id}", name=".show")
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        /**
         * @var User $user
         */
        if (!$user = $this->userRepository->find($request->get('userId'))) {
            throw new \RuntimeException("Пользователь с id  #{$request->get('userId')} не найден!");
        }

        /**
         * @var Advertisement $advertisement
         */
        if (!$advertisement = $this->advertisementRepository->find($request->attributes->get('id'))) {
            throw new \RuntimeException("Объявление с id  #{$request->attributes->get('id')} не найдено!");
        }
        if (!$statusDescription = $this->advertisementStatusTypeRepository->find($advertisement->getStatus()->getAdvertisementStatusType())) {
            throw new \RuntimeException("Статус пользователя с id  #{$user->getId()} не найден!");
        }
        $message = $advertisement->getStatus()->getMessage() ?? 'нет сообщения';
        $addressString = $advertisement->getAddress()->__toString();
        $fio = $user->getName()->getFIO();
        $sexDescription = Sex::SEX_VALUES[$user->getSex()->getSex()];
        $bodyAdvertisement = $advertisement->getBodyAdvertisement();
        return $this->render('app/advertisements/show.html.twig', compact( 'advertisement', 'fio', 'sexDescription', 'statusDescription', 'user', 'bodyAdvertisement', 'addressString', 'user', 'message'));
    }
}
