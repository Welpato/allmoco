<?php

namespace App\Controller;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractCRUDController extends AbstractBaseController
{
    protected const MAIN_ROUTE = '';
    protected const MAIN_ROUTE_NAME = '';
    protected const FORM_ROUTE = '';
    protected const FORM_ROUTE_NAME = '';
    protected const DELETE_ROUTE = '';
    protected const DELETE_ROUTE_NAME = '';


    /**
     * @var ServiceEntityRepositoryInterface
     */
    protected ServiceEntityRepositoryInterface $entityRepository;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * RestaurantController constructor.
     * @param ServiceEntityRepositoryInterface $entityRepository
     * @param EntityManagerInterface $entityManager
     */
    protected function __construct(
        ServiceEntityRepositoryInterface $entityRepository,
        EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $entityClass
     * @param array $entityList
     * @param string|null $idColumn
     * @return Response
     * @throws ReflectionException
     */
    protected function generateBaseGrid(
        string $entityClass,
        array $entityList,
        ?string $idColumn = null
    ): Response
    {
        $class = new ReflectionClass($entityClass);
        $columns = [];
        $classProperties = $class->getProperties();
        foreach ($classProperties as $property) {
            $columns[] = $property->getName();
        }
        if ($idColumn !== null) {
            $columns[] = '';
            $columns[] = '';
        }

        $rows = [];
        foreach ($entityList as $entity) {
            $row = [
                'values' => [],
                'id' => null,
            ];
            if ($idColumn !== null) {
                $getMethod = 'get' . (ucfirst($idColumn));
                $row['id'] = $entity->$getMethod();
            }

            foreach ($classProperties as $property) {
                $getMethod = 'get' . (ucfirst($property->getName()));
                $row['values'][] = $entity->$getMethod();
            }
            $rows[] = $row;
        }

        return $this->render(
            'base_view.html.twig',
            [
                'columns' => $columns,
                'rows' => $rows,
                'formRoute' => str_replace('{id}', '', static::FORM_ROUTE),
                'deleteRoute' => str_replace('{id}', '', static::DELETE_ROUTE),
            ]
        );
    }

    /**
     * @param string $entityClass
     * @param string $entityTypeClass
     * @param Request $request
     * @param int|null $id
     * @return Response
     */
    protected function baseFormPage(
        string $entityClass,
        string $entityTypeClass,
        Request $request,
        int $id = null
    ): Response
    {
        $entity = new $entityClass();
        if ($id !== null) {
            $entity = $this->entityRepository->find($id);
        }
        $form = $this->createForm($entityTypeClass, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $this->addFlash('success', 'Changes saved!');
            return $this->redirectToRoute(
                static::FORM_ROUTE_NAME,
                ['id' => null]
            );
        }
        return $this->render(
            'base_form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param string $entityClass
     * @param int|null $id
     * @return Response
     */
    protected function baseDeletePage(
        string $entityClass,
        int $id = null
    ): Response
    {
        if ($id === null) {
            return $this->redirectToRoute(static::MAIN_ROUTE_NAME);
        }
        $entity = $this->entityManager->getPartialReference(
            $entityClass,
            ['id' => $id]
        );

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $this->addFlash('success', 'Successful deletion');
        return $this->redirectToRoute(static::MAIN_ROUTE_NAME);
    }
}
