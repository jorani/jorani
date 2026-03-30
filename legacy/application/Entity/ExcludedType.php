<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant une exclusion de type de congé pour un contrat (table 'excluded_types').
 */
#[ORM\Entity]
#[ORM\Table(name: 'excluded_types')]
class ExcludedType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'contract_id', type: 'integer')]
    private int $contractId;

    #[ORM\Column(name: 'type_id', type: 'integer')]
    private int $typeId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractId(): int
    {
        return $this->contractId;
    }

    public function setContractId(int $contractId): self
    {
        $this->contractId = $contractId;
        return $this;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function setTypeId(int $typeId): self
    {
        $this->typeId = $typeId;
        return $this;
    }
}
