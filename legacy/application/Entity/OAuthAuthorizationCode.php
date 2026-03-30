<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un code d'autorisation OAuth (table 'oauth_authorization_codes').
 */
#[ORM\Entity]
#[ORM\Table(name: 'oauth_authorization_codes')]
class OAuthAuthorizationCode
{
    #[ORM\Id]
    #[ORM\Column(name: 'authorization_code', type: 'string', length: 40)]
    private string $authorizationCode;

    #[ORM\Column(name: 'client_id', type: 'string', length: 80)]
    private string $clientId;

    #[ORM\Column(name: 'user_id', type: 'string', length: 255, nullable: true)]
    private ?string $userId = null;

    #[ORM\Column(name: 'redirect_uri', type: 'string', length: 2000, nullable: true)]
    private ?string $redirectUri = null;

    #[ORM\Column(name: 'expires', type: 'datetime')]
    private DateTimeInterface $expires;

    #[ORM\Column(name: 'scope', type: 'string', length: 2000, nullable: true)]
    private ?string $scope = null;

    public function getAuthorizationCode(): string
    {
        return $this->authorizationCode;
    }

    public function setAuthorizationCode(string $authorizationCode): self
    {
        $this->authorizationCode = $authorizationCode;
        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    // Autres getters/setters abrégés
}
