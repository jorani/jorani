<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Entité représentant un utilisateur (table 'users').
 */
#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'firstname', type: 'string', length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(name: 'lastname', type: 'string', length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(name: 'login', type: 'string', length: 255, nullable: true)]
    private ?string $login = null;

    #[ORM\Column(name: 'email', type: 'string', length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'password', type: 'string', length: 512, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(name: 'role', type: 'integer', nullable: true)]
    private ?int $role = null;

    #[ORM\Column(name: 'manager', type: 'integer', nullable: true)]
    private ?int $manager = null;

    #[ORM\Column(name: 'country', type: 'integer', nullable: true)]
    private ?int $country = null;

    #[ORM\Column(name: 'organization', type: 'integer', options: ['default' => 0])]
    private ?int $organization = 0;

    #[ORM\Column(name: 'contract', type: 'integer', nullable: true)]
    private ?int $contract = null;

    #[ORM\Column(name: 'position', type: 'integer', nullable: true)]
    private ?int $position = null;

    #[ORM\Column(name: 'datehired', type: 'date', nullable: true)]
    private ?DateTimeInterface $datehired = null;

    #[ORM\Column(name: 'identifier', type: 'string', length: 64)]
    private string $identifier = '';

    #[ORM\Column(name: 'language', type: 'string', length: 5, options: ['default' => 'en'])]
    private string $language = 'en';

    #[ORM\Column(name: 'ldap_path', type: 'string', length: 1024, nullable: true)]
    private ?string $ldapPath = null;

    #[ORM\Column(name: 'active', type: 'boolean', options: ['default' => true])]
    private bool $active = true;

    #[ORM\Column(name: 'timezone', type: 'string', length: 255, nullable: true)]
    private ?string $timezone = null;

    #[ORM\Column(name: 'calendar', type: 'string', length: 255, nullable: true)]
    private ?string $calendar = null;

    #[ORM\Column(name: 'random_hash', type: 'string', length: 24, nullable: true)]
    private ?string $randomHash = null;

    #[ORM\Column(name: 'user_properties', type: 'text', nullable: true)]
    private ?string $userProperties = null;

    #[ORM\Column(name: 'picture', type: 'blob', nullable: true)]
    private $picture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(?int $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getManager(): ?int
    {
        return $this->manager;
    }

    public function setManager(?int $manager): self
    {
        $this->manager = $manager;
        return $this;
    }

    public function getCountry(): ?int
    {
        return $this->country;
    }

    public function setCountry(?int $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getOrganization(): ?int
    {
        return $this->organization;
    }

    public function setOrganization(?int $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    public function getContract(): ?int
    {
        return $this->contract;
    }

    public function setContract(?int $contract): self
    {
        $this->contract = $contract;
        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;
        return $this;
    }

    public function getDatehired(): ?DateTimeInterface
    {
        return $this->datehired;
    }

    public function setDatehired(?DateTimeInterface $datehired): self
    {
        $this->datehired = $datehired;
        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function getLdapPath(): ?string
    {
        return $this->ldapPath;
    }

    public function setLdapPath(?string $ldapPath): self
    {
        $this->ldapPath = $ldapPath;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;
        return $this;
    }

    public function getCalendar(): ?string
    {
        return $this->calendar;
    }

    public function setCalendar(?string $calendar): self
    {
        $this->calendar = $calendar;
        return $this;
    }

    public function getRandomHash(): ?string
    {
        return $this->randomHash;
    }

    public function setRandomHash(?string $randomHash): self
    {
        $this->randomHash = $randomHash;
        return $this;
    }

    public function getUserProperties(): ?string
    {
        return $this->userProperties;
    }

    public function setUserProperties(?string $userProperties): self
    {
        $this->userProperties = $userProperties;
        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;
        return $this;
    }
}
