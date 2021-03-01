<?php


namespace App\Controller;

use App\BusinessCase\SurveyResultsBusinessCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractBaseController
{

    public const INDEX_ROUTE_NAME = 'index_route';
    public const INDEX_ROUTE = '/';

    private SurveyResultsBusinessCase $surveyResultsBusinessCase;

    public function __construct(SurveyResultsBusinessCase $surveyResultsBusinessCase)
    {
        $this->surveyResultsBusinessCase = $surveyResultsBusinessCase;
    }

    /**
     * @Route(self::INDEX_ROUTE, name=self::INDEX_ROUTE_NAME)
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'results.html.twig',
            [
                'results' => $this->surveyResultsBusinessCase->getSurveyResults(),
            ]
        );
    }
}