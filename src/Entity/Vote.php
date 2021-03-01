<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="votes")
 */
class Vote
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(
     *     type="integer",
     *     options={"comment"="Vote ID"}
     * )
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @var int
     * @ORM\Column(
     *     name="surveyOption",
     *     type="integer",
     *     options={"comment"="The selected vote option"}
     * )
     */
    private int $surveyOption;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Survey")
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     */
    private int $survey;


    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private string $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * @return int|null
     */
    public function getSurveyOption(): ?int
    {
        return $this->surveyOption;
    }

    /**
     * @param int $surveyOption
     * @return $this
     */
    public function setSurveyOption(int $surveyOption): self
    {
        $this->surveyOption = $surveyOption;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return $this
     */
    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getSurvey(): int
    {
        return $this->survey;
    }

    /**
     * @param int $survey
     * @return $this
     */
    public function setSurvey(int $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

}
