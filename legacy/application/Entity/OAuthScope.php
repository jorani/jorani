<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un scope OAuth (table 'oauth_scopes').
 * Note: Table sans clé primaire définie dans jorani.sql. 
 * Doctrine exige une clé primaire. On utilise 'scope'.
 */
#[ORM\Entity]
#[ORM\Table(name: 'oauth_scopes')]
class OAuthScope
{
    #[ORM\Id]
    #[ORM\Column(name: 'scope', type: 'string')]
    private string $scope;

    #[ORM\Column(name: 'is_default', type: 'boolean', nullable: true)]
    private ?bool $isDefault = null;

    public function getScope(): string
    {
        return $this->scope;
    }

    public function isDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(?bool $isDefault): self
    {
        $this->isDefault = $isDefault;
        return $this;
    }
}
