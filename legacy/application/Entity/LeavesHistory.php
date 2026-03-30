<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant l'historique des changements de congés (table 'leaves_history').
 */
#[ORM\Entity]
#[ORM\Table(name: 'leaves_history')]
class LeavesHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'change_id', type: 'integer')]
    private ?int $changeId = null;

    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

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

    #[ORM\Column(name: 'duration', type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $duration = null;

    #[ORM\Column(name: 'type', type: 'integer', nullable: true)]
    private ?int $type = null;

    #[ORM\Column(name: 'comments', type: 'text', nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(name: 'document', type: 'blob', nullable: true)]
    private $document = null;

    #[ORM\Column(name: 'change_type', type: 'integer')]
    private int $changeType;

    #[ORM\Column(name: 'changed_by', type: 'integer')]
    private int $changedBy;

    #[ORM\Column(name: 'change_date', type: 'datetime')]
    private DateTimeInterface $changeDate;

    // Getters and setters (abridged for brevity)
    public function getChangeId(): ?int
    {
        return $this->changeId;
    }

    // ... we can add other getters/setters as needed
}
