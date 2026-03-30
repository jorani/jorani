<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un paramètre applicatif (table 'parameters').
 */
#[ORM\Entity]
#[ORM\Table(name: 'parameters')]
class Parameter
{
    #[ORM\Id]
    #[ORM\Column(name: 'name', type: 'string', length: 32)]
    private string $name;

    #[ORM\Id]
    #[ORM\Column(name: 'scope', type: 'integer')]
    private int $scope;

    #[ORM\Column(name: 'value', type: 'text')]
    private string $value;

    #[ORM\Column(name: 'entity_id', type: 'text', nullable: true)]
    private ?string $entityId = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getScope(): int
    {
        return $this->scope;
    }

    public function setScope(int $scope): self
    {
        $this->scope = $scope;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getEntityId(): ?string
    {
        return $this->entityId;
    }

    public function setEntityId(?string $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }
}
