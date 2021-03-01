<?php

namespace App\BusinessCase;

use App\Entity\Survey;
use App\Entity\Vote;
use App\Repository\SurveyRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class VoteBusinessCase
 */
class VoteBusinessCase
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
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * VoteBusinessCase constructor.
     * @param SurveyRepository $surveyRepository
     * @param VoteRepository $voteRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        SurveyRepository $surveyRepository,
        VoteRepository $voteRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->surveyRepository = $surveyRepository;
        $this->voteRepository = $voteRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Survey
     * @throws Exception
     */
    public function getActualSurvey(): Survey
    {
        $activeSurvey = $this->surveyRepository->findOneBy(
            [
                'active' => true,
                'date' => new \DateTime(),
            ]
        );

        if ($activeSurvey === null) {
            throw new Exception('There is no surveys open for today!');
        }

        return $activeSurvey;
    }

    /**
     * @param Vote $vote
     * @throws Exception
     */
    public function vote(Vote $vote): void
    {
        $alreadyVoted = $this->voteRepository->findOneBy(
            [
                'user' => $vote->getUser(),
                'survey' => $vote->getSurvey(),
            ]
        );

        if ($alreadyVoted !== null) {
            throw new Exception('You already voted on this survey!');
        }

        $this->entityManager->persist($vote);
        $this->entityManager->flush();
    }
}
