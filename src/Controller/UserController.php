<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 */
class UserController extends AbstractCRUDController
{
    public const MAIN_ROUTE = '/user';
    public const MAIN_ROUTE_NAME = 'user_main_route';
    public const FORM_ROUTE = '/user/form/{id}';
    public const FORM_ROUTE_NAME = 'user_form_route';
    public const DELETE_ROUTE = '/user/delete/{id}';
    public const DELETE_ROUTE_NAME = 'user_delete_route';
    public const PAGE_TITLE = 'User';


    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($userRepository, $entityManager);
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
            User::class,
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
            User::class,
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
            User::class,
            UserType::class,
            $request,
            $id
        );
    }

}
