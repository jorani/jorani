<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un JWT OAuth (table 'oauth_jwt').
 */
#[ORM\Entity]
#[ORM\Table(name: 'oauth_jwt')]
class OAuthJwt
{
    #[ORM\Id]
    #[ORM\Column(name: 'client_id', type: 'string', length: 80)]
    private string $clientId;

    #[ORM\Column(name: 'subject', type: 'string', length: 80, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(name: 'public_key', type: 'string', length: 2000, nullable: true)]
    private ?string $publicKey = null;

    public function getClientId(): string
    {
        return $this->clientId;
    }

    // Autres getters/setters
}
