<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Entité représentant une demande d'heures supplémentaires (table 'overtime').
 */
#[ORM\Entity]
#[ORM\Table(name: 'overtime')]
class Overtime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'employee', type: 'integer')]
    private int $employee;

    #[ORM\Column(name: 'date', type: 'date')]
    private DateTimeInterface $date;

    #[ORM\Column(name: 'duration', type: 'decimal', precision: 10, scale: 3)]
    private string $duration;

    #[ORM\Column(name: 'cause', type: 'text')]
    private string $cause;

    #[ORM\Column(name: 'status', type: 'integer')]
    private int $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): int
    {
        return $this->employee;
    }

    public function setEmployee(int $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getCause(): string
    {
        return $this->cause;
    }

    public function setCause(string $cause): self
    {
        $this->cause = $cause;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }
}
