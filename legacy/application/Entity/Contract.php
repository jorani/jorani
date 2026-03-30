<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Entité représentant un contrat (table 'contracts').
 */
#[ORM\Entity]
#[ORM\Table(name: 'contracts')]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'string', length: 128)]
    private string $name = '';

    #[ORM\Column(name: 'startentdate', type: 'string', length: 5)]
    private string $startentdate = '';

    #[ORM\Column(name: 'endentdate', type: 'string', length: 5)]
    private string $endentdate = '';

    #[ORM\Column(name: 'weekly_duration', type: 'integer', nullable: true)]
    private ?int $weeklyDuration = null;

    #[ORM\Column(name: 'daily_duration', type: 'integer', nullable: true)]
    private ?int $dailyDuration = null;

    #[ORM\Column(name: 'default_leave_type', type: 'integer', nullable: true)]
    private ?int $defaultLeaveType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStartentdate(): string
    {
        return $this->startentdate;
    }

    public function setStartentdate(string $startentdate): self
    {
        $this->startentdate = $startentdate;
        return $this;
    }

    public function getEndentdate(): string
    {
        return $this->endentdate;
    }

    public function setEndentdate(string $endentdate): self
    {
        $this->endentdate = $endentdate;
        return $this;
    }

    public function getWeeklyDuration(): ?int
    {
        return $this->weeklyDuration;
    }

    public function setWeeklyDuration(?int $weeklyDuration): self
    {
        $this->weeklyDuration = $weeklyDuration;
        return $this;
    }

    public function getDailyDuration(): ?int
    {
        return $this->dailyDuration;
    }

    public function setDailyDuration(?int $dailyDuration): self
    {
        $this->dailyDuration = $dailyDuration;
        return $this;
    }

    public function getDefaultLeaveType(): ?int
    {
        return $this->defaultLeaveType;
    }

    public function setDefaultLeaveType(?int $defaultLeaveType): self
    {
        $this->defaultLeaveType = $defaultLeaveType;
        return $this;
    }
}
