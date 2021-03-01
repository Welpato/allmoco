<?php

namespace App\BusinessCase;

use App\Entity\Survey;
use App\Repository\RestaurantRepository;
use App\Repository\SurveyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Class SurveyBusinessCase
 */
class SurveyBusinessCase
{
    /**
     * @var SurveyRepository
     */
    private SurveyRepository $surveyRepository;

    /**
     * @var RestaurantRepository
     */
    private RestaurantRepository $restaurantRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var SurveyResultsBusinessCase
     */
    private SurveyResultsBusinessCase $surveyResultBusinessCase;

    public function __construct(
        SurveyRepository $surveyRepository,
        SurveyResultsBusinessCase $surveyResultsBusinessCase,
        RestaurantRepository $restaurantRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->surveyRepository = $surveyRepository;
        $this->surveyResultBusinessCase = $surveyResultsBusinessCase;
        $this->restaurantRepository = $restaurantRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Survey $survey
     * @return bool
     * @throws Exception
     */
    public function surveyControl(Survey $survey): bool
    {
        if ($survey->getId() === null) {
            return $this->createNewSurvey($survey);
        }
        return $this->updateSurvey($survey);
    }

    /**
     * @param Survey $survey
     * @return bool
     * @throws Exception
     */
    private function createNewSurvey(Survey $survey): bool
    {
        $checkSurvey = $this->surveyRepository->findOneBy(
            [
                'date' => $survey->getDate(),
                'active' => true,
            ]
        );
        if ($checkSurvey !== null) {
            throw new Exception('Already exists an active survey for this day');
        }
        $survey->setActive(true);
        $survey->setOptions($this->getOptions());

        $this->entityManager->persist($survey);
        $this->entityManager->flush();

        return true;
    }

    private function getOptions(): array
    {
        $options = [];
        $resultList = $this->surveyResultBusinessCase->getSurveyResults();

        $winnersOfTheWeek = [];

        $date = new \DateTime();
        $weekDays = [];
        while (count($weekDays) < 7) {
            $weekDays[] = (new \DateTime())->setISODate(
                (int)$date->format('o'),
                (int)$date->format('w') + 2,
                count($weekDays)
            )->format('d-m-Y');
        }
        foreach ($resultList as $result) {
            $resultDate = \DateTime::createFromFormat('d-m-Y', $result['date'])
                ->format('d-m-Y');
            if (!in_array($resultDate, $weekDays)) {
                continue;
            }
            $winnersOfTheWeek[] = $result['winnerId'];
        }

        foreach ($this->restaurantRepository->findAll() as $restaurant) {
            if (in_array($restaurant->getId(), $winnersOfTheWeek)) {
                continue;
            }

            $options[] = [
                'id' => $restaurant->getId(),
                'name' => $restaurant->getName(),
                'address' => $restaurant->getAddress(),
            ];
        }

        return $options;
    }

    /**
     * @param $survey
     * @return bool
     */
    private function updateSurvey($survey): bool
    {
        $this->entityManager->persist($survey);
        $this->entityManager->flush();

        return true;
    }
}
