<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un token d'accès OAuth (table 'oauth_access_tokens').
 */
#[ORM\Entity]
#[ORM\Table(name: 'oauth_access_tokens')]
class OAuthAccessToken
{
    #[ORM\Id]
    #[ORM\Column(name: 'access_token', type: 'string', length: 40)]
    private string $accessToken;

    #[ORM\Column(name: 'client_id', type: 'string', length: 80)]
    private string $clientId;

    #[ORM\Column(name: 'user_id', type: 'string', length: 255, nullable: true)]
    private ?string $userId = null;

    #[ORM\Column(name: 'expires', type: 'datetime')]
    private DateTimeInterface $expires;

    #[ORM\Column(name: 'scope', type: 'string', length: 2000, nullable: true)]
    private ?string $scope = null;

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;
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

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getExpires(): DateTimeInterface
    {
        return $this->expires;
    }

    public function setExpires(DateTimeInterface $expires): self
    {
        $this->expires = $expires;
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
}
