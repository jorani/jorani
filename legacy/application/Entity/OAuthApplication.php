<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant une application OAuth (table 'oauth_applications').
 */
#[ORM\Entity]
#[ORM\Table(name: 'oauth_applications')]
class OAuthApplication
{
    #[ORM\Id]
    #[ORM\Column(name: 'user', type: 'integer')]
    private int $user;

    #[ORM\Id]
    #[ORM\Column(name: 'client_id', type: 'string', length: 80)]
    private string $clientId;

    public function getUser(): int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }
}
