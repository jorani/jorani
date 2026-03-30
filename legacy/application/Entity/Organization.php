<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Entité représentant un département / une organisation (table 'organization').
 */
#[ORM\Entity]
#[ORM\Table(name: 'organization')]
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: 'string', length: 512, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'parent_id', type: 'integer', nullable: true)]
    private ?int $parentId = null;

    #[ORM\Column(name: 'supervisor', type: 'integer', nullable: true)]
    private ?int $supervisor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;
        return $this;
    }

    public function getSupervisor(): ?int
    {
        return $this->supervisor;
    }

    public function setSupervisor(?int $supervisor): self
    {
        $this->supervisor = $supervisor;
        return $this;
    }
}
