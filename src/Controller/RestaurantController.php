<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestaurantController
 */
class RestaurantController extends AbstractCRUDController
{
    public const MAIN_ROUTE = '/restaurant';
    public const MAIN_ROUTE_NAME = 'restaurant_main_route';
    public const FORM_ROUTE = '/restaurant/form/{id}';
    public const FORM_ROUTE_NAME = 'restaurant_form_route';
    public const DELETE_ROUTE = '/restaurant/delete/{id}';
    public const DELETE_ROUTE_NAME = 'restaurant_delete_route';
    public const PAGE_TITLE = 'Restaurant';

    /**
     * RestaurantController constructor.
     * @param RestaurantRepository $restaurantRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        RestaurantRepository $restaurantRepository,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($restaurantRepository, $entityManager);
    }

    /**
     * @Route(self::MAIN_ROUTE, name=self::MAIN_ROUTE_NAME)
     * @param Request $request
     * @return Response
     * @throws ReflectionException
     */
    public function mainPage(Request $request): Response
    {
        return $this->generateBaseGrid(
            Restaurant::class,
            $this->entityRepository->findAll(),
            'id'
        );
    }

    /**
     * @Route(self::DELETE_ROUTE, name=self::DELETE_ROUTE_NAME)
     * @param Request $request
     * @param int|null $id
     * @return Response
     */
    public function deletePage(Request $request, int $id = null): Response
    {
        return parent::baseDeletePage(
            Restaurant::class,
            $id
        );
    }

    /**
     * @Route(self::FORM_ROUTE, name=self::FORM_ROUTE_NAME)
     * @param Request $request
     * @param int|null $id
     * @return Response
     */
    public function formPage(Request $request, int $id = null): Response
    {
        return parent::baseFormPage(
            Restaurant::class,
            RestaurantType::class,
            $request,
            $id
        );
    }

}
