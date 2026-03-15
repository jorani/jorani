<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class LeaveType
{
    #[OA\Property(property: "id", type: "integer", description: "Unique identifier of the type")]
    public int $id;
    #[OA\Property(property: "name", type: "string", description: "Name of the leave type")]
    public string $name;
    #[OA\Property(property: "acronym", type: "string", description: "Acronym of the leave type")]
    public string $acronym;
    #[OA\Property(property: "deduct_days_off", type: "boolean", description: "Deduct days off when computing the balance of the leave type")]
    public bool $deduct_days_off;
}
