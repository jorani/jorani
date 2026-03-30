<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un type de congé (table 'types').
 */
#[ORM\Entity(repositoryClass: \App\Repository\TypeRepository::class)]
#[ORM\Table(name: 'types')]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'string', length: 128)]
    private string $name;

    #[ORM\Column(name: 'acronym', type: 'string', length: 10, nullable: true)]
    private ?string $acronym = null;

    #[ORM\Column(name: 'deduct_days_off', type: 'boolean', options: ['default' => false])]
    private bool $deductDaysOff = false;

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

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(?string $acronym): self
    {
        $this->acronym = $acronym;
        return $this;
    }

    public function isDeductDaysOff(): bool
    {
        return $this->deductDaysOff;
    }

    public function setDeductDaysOff(bool $deductDaysOff): self
    {
        $this->deductDaysOff = $deductDaysOff;
        return $this;
    }
}
