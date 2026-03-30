<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Entité représentant un utilisateur OAuth (table 'oauth_users').
 */
#[ORM\Entity]
#[ORM\Table(name: 'oauth_users')]
class OAuthUser
{
    #[ORM\Id]
    #[ORM\Column(name: 'username', type: 'string', length: 255)]
    private string $username;

    #[ORM\Column(name: 'password', type: 'string', length: 2000, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(name: 'first_name', type: 'string', length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(name: 'last_name', type: 'string', length: 255, nullable: true)]
    private ?string $lastName = null;

    public function getUsername(): string
    {
        return $this->username;
    }

    // Autres getters/setters
}
