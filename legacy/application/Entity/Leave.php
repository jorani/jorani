<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Entité représentant une demande de congé (table 'leaves').
 */
#[ORM\Entity]
#[ORM\Table(name: 'leaves')]
class Leave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'startdate', type: 'date', nullable: true)]
    private ?DateTimeInterface $startdate = null;

    #[ORM\Column(name: 'enddate', type: 'date', nullable: true)]
    private ?DateTimeInterface $enddate = null;

    #[ORM\Column(name: 'status', type: 'integer', nullable: true)]
    private ?int $status = null;

    #[ORM\Column(name: 'employee', type: 'integer', nullable: true)]
    private ?int $employee = null;

    #[ORM\Column(name: 'cause', type: 'text', nullable: true)]
    private ?string $cause = null;

    #[ORM\Column(name: 'startdatetype', type: 'string', length: 12, nullable: true)]
    private ?string $startdatetype = null;

    #[ORM\Column(name: 'enddatetype', type: 'string', length: 12, nullable: true)]
    private ?string $enddatetype = null;

    #[ORM\Column(name: 'duration', type: 'decimal', precision: 10, scale: 3, nullable: true)]
    private ?string $duration = null;

    #[ORM\Column(name: 'type', type: 'integer', nullable: true)]
    private ?int $type = null;

    #[ORM\Column(name: 'comments', type: 'text', nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(name: 'document', type: 'blob', nullable: true)]
    private $document = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;
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

    public function getCause(): ?string
    {
        return $this->cause;
    }

    public function setCause(?string $cause): self
    {
        $this->cause = $cause;
        return $this;
    }

    public function getStartdatetype(): ?string
    {
        return $this->startdatetype;
    }

    public function setStartdatetype(?string $startdatetype): self
    {
        $this->startdatetype = $startdatetype;
        return $this;
    }

    public function getEnddatetype(): ?string
    {
        return $this->enddatetype;
    }

    public function setEnddatetype(?string $enddatetype): self
    {
        $this->enddatetype = $enddatetype;
        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setDocument($document): self
    {
        $this->document = $document;
        return $this;
    }
}
