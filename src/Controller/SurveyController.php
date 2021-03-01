<?php

namespace App\Controller;

use App\BusinessCase\SurveyBusinessCase;
use App\Entity\Survey;
use App\Form\SurveyType;
use App\Repository\SurveyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SurveyController
 */
class SurveyController extends AbstractCRUDController
{
    public const MAIN_ROUTE = '/survey';
    public const MAIN_ROUTE_NAME = 'survey_main_route';
    public const FORM_ROUTE = '/survey/form/{id}';
    public const FORM_ROUTE_NAME = 'survey_form_route';
    public const DELETE_ROUTE = '/survey/delete/{id}';
    public const DELETE_ROUTE_NAME = 'survey_delete_route';
    public const PAGE_TITLE = 'Survey Maintenance';

    private SurveyBusinessCase $surveyBusinessCase;

    /**
     * SurveyController constructor.
     * @param SurveyRepository $surveyRepository
     * @param EntityManagerInterface $entityManager
     * @param SurveyBusinessCase $surveyBusinessCase
     */
    public function __construct(
        SurveyRepository $surveyRepository,
        EntityManagerInterface $entityManager,
        SurveyBusinessCase $surveyBusinessCase
    )
    {
        parent::__construct($surveyRepository, $entityManager);
        $this->surveyBusinessCase = $surveyBusinessCase;
    }

    /**
     * @Route(self::MAIN_ROUTE, name=self::MAIN_ROUTE_NAME)
     * @param Request $request
     * @return Response
     */
    public function mainPage(Request $request): Response
    {
        $surveyList = $this->entityRepository->findAll();
        $rows = [];
        foreach ($surveyList as $survey){
            $rows[] = [
                'values' => [
                    $survey->getId(),
                    $survey->getDate()->format('d-m-Y'),
                    $survey->isActive() ? 'Active' : 'Inactive',
                ],
                'id' => $survey->getId(),
            ];
        }
        return $this->render(
            'base_view.html.twig',
            [
                'columns' => [
                    'ID',
                    'Date',
                    'Is active',
                    '',
                    ''
                ],
                'rows' => $rows,
                'formRoute' => str_replace('{id}', '', static::FORM_ROUTE),
                'deleteRoute' => str_replace('{id}', '', static::DELETE_ROUTE),
            ]
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
            Survey::class,
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
        $survey = new Survey();
        $options = [];
        if ($id !== null) {
            $survey = $this->entityRepository->find($id);
            $options['isNew'] = false;
        }
        $form = $this->createForm(SurveyType::class, $survey, $options);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();

            try{
                if ($this->surveyBusinessCase->surveyControl($survey)) {
                    $this->addFlash('success', 'Changes saved!');
                    return $this->redirectToRoute(
                        static::MAIN_ROUTE_NAME
                    );
                }
            }catch (\Exception $e){
                $this->addFlash('danger',$e->getMessage());
            }

        }
        return $this->render(
            'base_form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
