<?php

namespace App\Entity;

use DateTime;
use App\Repository\SurveyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * @ORM\Entity
 * @ORM\Table(name="survey")
 */
class Survey
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(
     *     type="integer",
     *     options={"comment"="Survey ID"}
     * )
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @var DateTime
     * @ORM\Column(
     *     type="date",
     *     options={"comment"="Survey date"}
     * )
     */
    private DateTime $date;

    /**
     * @var bool
     * @ORM\Column(
     *     type="boolean",
     *     options={"comment"="Check if the survey is active"}
     * )
     */
    private bool $active;

    /**
     * @var array
     * @ORM\Column(
     *     name="options",
     *     type="array",
     *     nullable=true
     *)
     */
    private array $options;

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        if($id === null)
        {
            return $this;
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
