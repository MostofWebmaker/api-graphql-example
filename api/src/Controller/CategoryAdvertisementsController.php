<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Advertisement\Command\CategoryAdvertisement\CreateCategoryAdvertisementRequestCommand;
use App\Model\Advertisement\Command\CategoryAdvertisement\DeleteCategoryAdvertisementRequestCommand;
use App\Model\Advertisement\Command\CategoryAdvertisement\UpdateCategoryAdvertisementRequestCommand;
use App\Model\Advertisement\Entity\CategoryAdvertisement;
use App\Model\Advertisement\Form\CategoryAdvertisement\DeleteCategoryAdvertisementRequestFormType;
use App\Model\Advertisement\Repository\CategoryAdvertisementRepository;
use App\Model\Features\Advertisement\CategoryAdvertisement\CategoryAdvertisementCreateFeature;
use App\Model\Features\Advertisement\CategoryAdvertisement\CategoryAdvertisementDeleteFeature;
use App\Model\Features\Advertisement\CategoryAdvertisement\CategoryAdvertisementUpdateFeature;
use App\ReadModel\Advertisement\Filter\AdvertisementFilter;
use App\ReadModel\Advertisement\Filter\AdvertisementForm;
use App\Model\User\Repository\UserRepository;
use App\ReadModel\CategoryAdvertisement\CategoryAdvertisementFetcher;
use App\ReadModel\CategoryAdvertisement\Create\CreateCategoryAdvertisementCommand;
use App\ReadModel\CategoryAdvertisement\Create\CreateCategoryAdvertisementForm;
use App\ReadModel\CategoryAdvertisement\Update\UpdateCategoryAdvertisementCommand;
use App\ReadModel\CategoryAdvertisement\Update\UpdateCategoryAdvertisementForm;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories", name="categories")
 * @IsGranted("ROLE_ADMIN")
 */
class CategoryAdvertisementsController extends AbstractController
{
    private const PER_PAGE = 50;

    private ErrorHandler $errors;

    /** @var FormFactoryInterface */
    protected FormFactoryInterface $formFactory;
    /**
     * @var CategoryAdvertisementRepository
     */
    private CategoryAdvertisementRepository $categoryAdvertisementRepository;

    /**
     * @var CategoryAdvertisementCreateFeature
     */
    private CategoryAdvertisementCreateFeature $categoryAdvertisementCreateFeature;

    /**
     * @var CategoryAdvertisementUpdateFeature
     */
    private CategoryAdvertisementUpdateFeature $categoryAdvertisementUpdateFeature;

    /**
     * @var CategoryAdvertisementDeleteFeature
     */
    private CategoryAdvertisementDeleteFeature $categoryAdvertisementDeleteFeature;

    /**
     * CategoryAdvertisementsController constructor.
     * @param ErrorHandler $errors
     * @param FormFactoryInterface $formFactory
     * @param CategoryAdvertisementRepository $categoryAdvertisementRepository
     * @param CategoryAdvertisementCreateFeature $categoryAdvertisementCreateFeature
     * @param CategoryAdvertisementUpdateFeature $categoryAdvertisementUpdateFeature
     * @param CategoryAdvertisementDeleteFeature $categoryAdvertisementDeleteFeature
     */
    public function __construct(
        ErrorHandler $errors,
        FormFactoryInterface $formFactory,
              CategoryAdvertisementRepository $categoryAdvertisementRepository,
        CategoryAdvertisementCreateFeature $categoryAdvertisementCreateFeature,
        CategoryAdvertisementUpdateFeature $categoryAdvertisementUpdateFeature,
        CategoryAdvertisementDeleteFeature $categoryAdvertisementDeleteFeature
    ) {
        $this->errors = $errors;
        $this->formFactory = $formFactory;
        $this->categoryAdvertisementRepository = $categoryAdvertisementRepository;
        $this->categoryAdvertisementCreateFeature = $categoryAdvertisementCreateFeature;
        $this->categoryAdvertisementUpdateFeature = $categoryAdvertisementUpdateFeature;
        $this->categoryAdvertisementDeleteFeature = $categoryAdvertisementDeleteFeature;
    }

    /**
     * @Route("", name="")
     * @param Request $request
     * @param CategoryAdvertisementFetcher $fetcher
     * @return Response
     */
    public function index(Request $request, CategoryAdvertisementFetcher $fetcher): Response
    {
        $form = $this->createForm(AdvertisementForm::class);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'date_created'),
            $request->query->get('direction', 'desc'),
        );

        return $this->render('app/categories/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name=".create")
     * @param Request $request
     * @param CreateCategoryAdvertisementCommand $command
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Request $request, CreateCategoryAdvertisementCommand $command): Response
    {
        $form = $this->createForm(CreateCategoryAdvertisementForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $featureCommand = new CreateCategoryAdvertisementRequestCommand();
                if ($command->getTitle()) {
                    $featureCommand->setTitle($command->getTitle());
                }
                if ($command->getDescription()) {
                    $featureCommand->setDescription($command->getDescription());
                }
                $this->categoryAdvertisementCreateFeature->createCategoryAdvertisement($featureCommand);
                return $this->redirectToRoute('categories');
            } catch (\RuntimeException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/categories/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name=".edit")
     * @param Request $request
     * @param UpdateCategoryAdvertisementCommand $command
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function edit(Request $request, UpdateCategoryAdvertisementCommand $command): Response
    {
        /**
         * @var CategoryAdvertisement $category
         */
        if (!$category = $this->categoryAdvertisementRepository->find($request->attributes->get('id'))) {
            throw new \RuntimeException("Категория с id  #{$request->attributes->get('id')} не найдена!");
        }
        $command->setTitle($category->getTitle());
        $command->setDescription($category->getDescription());
        $form = $this->createForm(UpdateCategoryAdvertisementForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $featureCommand = new UpdateCategoryAdvertisementRequestCommand();
                $featureCommand->setId($category->getId());
                if ($command->getTitle()) {
                    $featureCommand->setTitle($command->getTitle());
                }
                if ($command->getDescription()) {
                    $featureCommand->setDescription($command->getDescription());
                }
                $this->categoryAdvertisementUpdateFeature->updateCategoryAdvertisement($featureCommand);
                return $this->redirectToRoute('categories.show', ['id' => $category->getId()]);
            } catch (\RuntimeException $e) {
                $this->addFlash('error', $e->getMessage());
                $this->errors->handle($e);
                throw new \RuntimeException($e->getMessage());
            }
        }
        return $this->render('app/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name=".delete")
     * @param Request $request
     * @param DeleteCategoryAdvertisementRequestCommand $command
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Request $request, DeleteCategoryAdvertisementRequestCommand $command): Response
    {
        /**
         * @var CategoryAdvertisement $category
         */
        if (!$category = $this->categoryAdvertisementRepository->find($request->attributes->get('id'))) {
            throw new \RuntimeException("Категория с id  #{$request->attributes->get('id')} не найдена!");
        }
        $command->setId($category->getId());
        $form = $this->createForm(DeleteCategoryAdvertisementRequestFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                //dd($command);
                $this->categoryAdvertisementDeleteFeature->deleteCategoryAdvertisement($command);
                return $this->redirectToRoute('categories');
            } catch (\RuntimeException $e) {
                $this->addFlash('error', $e->getMessage());
                $this->errors->handle($e);
                throw new \RuntimeException($e->getMessage());
            }
        }
        return $this->render('app/categories/delete.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name=".show")
     * @param CategoryAdvertisement $category
     * @return Response
     */
    public function show(CategoryAdvertisement $category): Response
    {
        return $this->render('app/categories/show.html.twig', compact( 'category'));
    }
}
