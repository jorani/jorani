<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class User
{
    #[OA\Property(property: "id", type: "integer", description: "Unique identifier of the user")]
    public int $id;
    #[OA\Property(property: "firstname", type: "string", description: "First name")]
    public string $firstname;
    #[OA\Property(property: "lastname", type: "string", description: "Last name")]
    public string $lastname;
    #[OA\Property(property: "login", type: "string", description: "Identfier used to login (can be an email address)")]
    public string $login;
    #[OA\Property(property: "email", type: "string", description: "Email address")]
    public string $email;
    #[OA\Property(property: "role", type: "integer", description: "Role of the employee (binary mask). See table roles.")]
    public int $role;
    #[OA\Property(property: "manager", type: "integer", description: "Employee validating the requests of the employee")]
    public int $manager;
    #[OA\Property(property: "country", type: "integer", description: "Country code (for later use)")]
    public int $country;
    #[OA\Property(property: "organization", type: "integer", description: "Entity where the employee has a position")]
    public int $organization;
    #[OA\Property(property: "contract", type: "integer", description: "Contract of the employee")]
    public int $contract;
    #[OA\Property(property: "position", type: "integer", description: "Position of the employee")]
    public int $position;
    #[OA\Property(property: "datehired", type: "string", description: "Date hired / Started")]
    public string $datehired;
    #[OA\Property(property: "identifier", type: "string", description: "Internal/company identifier")]
    public string $identifier;
    #[OA\Property(property: "language", type: "string", description: "Language ISO code")]
    public string $language;
    #[OA\Property(property: "ldap_path", type: "string", description: "LDAP Path for complex authentication schemes")]
    public string $ldap_path;
    #[OA\Property(property: "active", type: "boolean", description: "Is user active")]
    public bool $active;
    #[OA\Property(property: "timezone", type: "string", description: "Timezone of user")]
    public string $timezone;
    #[OA\Property(property: "calendar", type: "string", description: "External Calendar address")]
    public string $calendar;
    #[OA\Property(property: "user_properties", type: "string", description: "Entity ID (eg. user id) to which the parameter is applied")]
    public string $user_properties;
    #[OA\Property(property: "picture", type: "string", description: "Picture of the user")]
    public string $picture;
}
