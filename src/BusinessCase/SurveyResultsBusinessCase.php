<?php
namespace App\BusinessCase;

use App\Repository\SurveyRepository;
use App\Repository\VoteRepository;

/**
 * Class SurveyResultsBusinessCase
 */
class SurveyResultsBusinessCase
{
    /**
     * @var SurveyRepository
     */
    private SurveyRepository $surveyRepository;

    /**
     * @var VoteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * SurveyResultsBusinessCase constructor.
     * @param SurveyRepository $surveyRepository
     * @param VoteRepository $voteRepository
     */
    public function __construct(
        SurveyRepository $surveyRepository,
        VoteRepository $voteRepository
    )
    {
        $this->surveyRepository = $surveyRepository;
        $this->voteRepository = $voteRepository;
    }

    /**
     * Returns an empty array when no active survey was found
     * @return array
     */
    public function getSurveyResults(): array
    {
        $surveyList = $this->surveyRepository->findBy(
            [
                'active' => false,
            ]
        );

        $surveyResults = [];
        foreach ($surveyList as $survey) {
            $votes = $this->voteRepository->findBy(
                [
                    'survey' => $survey->getId(),
                ]
            );
            $options = [];
            foreach ($survey->getOptions() as $option) {
                $options[$option['id']] = [
                    'name' => $option['name'],
                    'address' => $option['address'],
                    'totalVotes' => 0,
                ];
            }
            foreach ($votes as $vote) {
                $options[$vote->getSurveyOption()]['totalVotes']++;
            }
            $winnerId = 0;
            foreach ($options as $id => $option) {
                if (
                    !isset($options[$winnerId]['totalVotes']) ||
                    $options[$winnerId]['totalVotes'] < $option['totalVotes']
                ) {
                    $winnerId = $id;
                }
            }
            $surveyResults[] = [
                'date' => $survey->getDate()->format('d-m-Y'),
                'id' => $survey->getId(),
                'options' => $options,
                'winnerId' => $winnerId,
            ];

        }

        return $surveyResults;
    }
}