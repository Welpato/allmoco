<?php

namespace App\Controller;

use App\BusinessCase\VoteBusinessCase;
use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VoteController
 */
class VoteController extends AbstractCRUDController
{
    public const MAIN_ROUTE = '/vote';
    public const MAIN_ROUTE_NAME = 'vote_main_route';
    public const PAGE_TITLE = 'Vote for the next lunch!';

    /**
     * @var VoteBusinessCase
     */
    private VoteBusinessCase $voteBusinessCase;

    public function __construct(
        VoteRepository $entityRepository,
        EntityManagerInterface $entityManager,
        VoteBusinessCase $voteBusinessCase
    )
    {
        parent::__construct($entityRepository, $entityManager);
        $this->voteBusinessCase = $voteBusinessCase;
    }

    /**
     * @Route(self::MAIN_ROUTE, name=self::MAIN_ROUTE_NAME)
     * @param Request $request
     * @return Response
     */
    public function votePage(Request $request): Response
    {
        try {
            $activeSurvey = $this->voteBusinessCase->getActualSurvey();
            $vote = new Vote();
            $vote->setSurvey($activeSurvey->getId());
            $options = ['restaurants' => []];
            foreach ($activeSurvey->getOptions() as $restaurants) {
                $options['restaurants'][] = [
                    $restaurants['name'] => $restaurants['id'],
                ];
            }

            $form = $this->createForm(VoteType::class, $vote, $options);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $vote = $form->getData();
                $this->voteBusinessCase->vote($vote);
                $this->addFlash('success','Thanks for your vote!');
                return $this->redirectToRoute(IndexController::INDEX_ROUTE_NAME);
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute(IndexController::INDEX_ROUTE_NAME);
        }
        return $this->render(
            'base_form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
