<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un refresh token OAuth (table 'oauth_refresh_tokens').
 */
#[ORM\Entity]
#[ORM\Table(name: 'oauth_refresh_tokens')]
class OAuthRefreshToken
{
    #[ORM\Id]
    #[ORM\Column(name: 'refresh_token', type: 'string', length: 40)]
    private string $refreshToken;

    #[ORM\Column(name: 'client_id', type: 'string', length: 80)]
    private string $clientId;

    #[ORM\Column(name: 'user_id', type: 'string', length: 255, nullable: true)]
    private ?string $userId = null;

    #[ORM\Column(name: 'expires', type: 'datetime')]
    private DateTimeInterface $expires;

    #[ORM\Column(name: 'scope', type: 'string', length: 2000, nullable: true)]
    private ?string $scope = null;

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    // Autres getters/setters abrégés
}
