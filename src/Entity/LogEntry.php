<?php

namespace App\Entity;

use App\Repository\LogEntryRepository;
use App\Type\DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogEntryRepository::class)
 * @ORM\Table(name="log_entries")
 */
class LogEntry
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $generatorId;

    /**
     * @ORM\Column(type="float")
     */
    private $power;

    /**
     * @ORM\Id()
     * @ORM\Column(type="datetime")
     */
    private $measurementTime;

    public function getGeneratorId(): ?int
    {
        return $this->generatorId;
    }

    public function setGeneratorId(int $generatorId): self
    {
        $this->generatorId = $generatorId;

        return $this;
    }

    public function getPower(): ?float
    {
        return $this->power;
    }

    public function setPower(float $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getMeasurementTime(): ?DateTime
    {
        return $this->measurementTime;
    }

    public function setMeasurementTime(DateTime $measurementTime): self
    {
        $this->measurementTime = $measurementTime;

        return $this;
    }
}
