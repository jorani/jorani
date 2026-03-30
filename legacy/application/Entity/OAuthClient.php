<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un client OAuth2 (table 'oauth_clients').
 */
#[ORM\Entity]
#[ORM\Table(name: 'oauth_clients')]
class OAuthClient
{
    #[ORM\Id]
    #[ORM\Column(name: 'client_id', type: 'string', length: 80)]
    private string $clientId;

    #[ORM\Column(name: 'client_secret', type: 'string', length: 80, nullable: true)]
    private ?string $clientSecret = null;

    #[ORM\Column(name: 'redirect_uri', type: 'string', length: 2000)]
    private string $redirectUri;

    #[ORM\Column(name: 'grant_types', type: 'string', length: 80, nullable: true)]
    private ?string $grantTypes = null;

    #[ORM\Column(name: 'scope', type: 'string', length: 100, nullable: true)]
    private ?string $scope = null;

    #[ORM\Column(name: 'user_id', type: 'string', length: 80, nullable: true)]
    private ?string $userId = null;

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(?string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri(string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    public function getGrantTypes(): ?string
    {
        return $this->grantTypes;
    }

    public function setGrantTypes(?string $grantTypes): self
    {
        $this->grantTypes = $grantTypes;
        return $this;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setScope(?string $scope): self
    {
        $this->scope = $scope;
        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }
}
