<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Entité représentant les droits à congés (table 'entitleddays').
 */
#[ORM\Entity]
#[ORM\Table(name: 'entitleddays')]
class Entitledday
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'contract', type: 'integer', nullable: true)]
    private ?int $contract = null;

    #[ORM\Column(name: 'employee', type: 'integer', nullable: true)]
    private ?int $employee = null;

    #[ORM\Column(name: 'overtime', type: 'integer', nullable: true)]
    private ?int $overtime = null;

    #[ORM\Column(name: 'startdate', type: 'date', nullable: true)]
    private ?DateTimeInterface $startdate = null;

    #[ORM\Column(name: 'enddate', type: 'date', nullable: true)]
    private ?DateTimeInterface $enddate = null;

    #[ORM\Column(name: 'type', type: 'integer')]
    private int $type;

    #[ORM\Column(name: 'days', type: 'decimal', precision: 10, scale: 2)]
    private string $days;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContract(): ?int
    {
        return $this->contract;
    }

    public function setContract(?int $contract): self
    {
        $this->contract = $contract;
        return $this;
    }

    public function getEmployee(): ?int
    {
        return $this->employee;
    }

    public function setEmployee(?int $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getOvertime(): ?int
    {
        return $this->overtime;
    }

    public function setOvertime(?int $overtime): self
    {
        $this->overtime = $overtime;
        return $this;
    }

    public function getStartdate(): ?DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(?DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;
        return $this;
    }

    public function getEnddate(): ?DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(?DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDays(): string
    {
        return $this->days;
    }

    public function setDays(string $days): self
    {
        $this->days = $days;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
