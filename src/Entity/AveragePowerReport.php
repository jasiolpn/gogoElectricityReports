<?php

namespace App\Entity;

use App\Repository\AveragePowerReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AveragePowerReportRepository::class)
 * @ORM\Table(name="average_power_reports")
 */
class AveragePowerReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $generatorId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $average_power;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGeneratorId(): ?int
    {
        return $this->generatorId;
    }

    public function setGeneratorId(int $generatorId): self
    {
        $this->generatorId = $generatorId;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAveragePower(): ?float
    {
        return $this->average_power;
    }

    public function setAveragePower(float $average_power): self
    {
        $this->average_power = $average_power;

        return $this;
    }
}
