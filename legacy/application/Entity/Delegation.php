<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant une délégation d'approbation (table 'delegations').
 */
#[ORM\Entity]
#[ORM\Table(name: 'delegations')]
class Delegation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'manager_id', type: 'integer')]
    private int $managerId;

    #[ORM\Column(name: 'delegate_id', type: 'integer')]
    private int $delegateId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManagerId(): int
    {
        return $this->managerId;
    }

    public function setManagerId(int $managerId): self
    {
        $this->managerId = $managerId;
        return $this;
    }

    public function getDelegateId(): int
    {
        return $this->delegateId;
    }

    public function setDelegateId(int $delegateId): self
    {
        $this->delegateId = $delegateId;
        return $this;
    }
}
