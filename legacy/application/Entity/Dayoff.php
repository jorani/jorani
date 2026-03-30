<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Entité représentant un jour non travaillé (table 'dayoffs').
 */
#[ORM\Entity]
#[ORM\Table(name: 'dayoffs')]
class Dayoff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'contract', type: 'integer')]
    private int $contract;

    #[ORM\Column(name: 'date', type: 'date')]
    private DateTimeInterface $date;

    #[ORM\Column(name: 'type', type: 'integer')]
    private int $type;

    #[ORM\Column(name: 'title', type: 'string', length: 128)]
    private string $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContract(): int
    {
        return $this->contract;
    }

    public function setContract(int $contract): self
    {
        $this->contract = $contract;
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

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
}
