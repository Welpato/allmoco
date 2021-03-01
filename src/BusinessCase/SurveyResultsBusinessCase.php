<?php### What to do best?
Due to leak of time I could not implement proper unit tests,
which I would really like to do if I had a bit more of free
time to invest.

For a better application I would really implement a proper
database like MySQL and not SQLite, and also implement some
mechanism of login and user insertion ( which I started
but gave up of doing it during the development, like
you will probably see inside my code).

I would also think again about the database structure
to add more co relational tables and make everything more
reliable.

### What I have more to say
I really liked doing this test and probably with more time I would like to put some extra work on it
( because choosing where everyone in the team is going
to eat... is a real issue, not much now during this
pandemic but you know).
And like I said before I did not have much free time
this week and with that I ended just working on this
during the dawn of the weekend. Which I also believe
that reduced my actual results.

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