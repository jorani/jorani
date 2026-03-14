<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: "Contract")]
class Contract
{
    #[OA\Property(property: "id", type: "integer", description: "Unique identifier of a contract")]
    public int $id;
    #[OA\Property(property: "name", type: "string", description: "Name of the contract")]
    public string $name;
    #[OA\Property(property: "startentdate", type: "string", pattern: "^\\d{2}/\\d{2}$", description: "Day and month numbers of the left boundary")]
    public string $startentdate;
    #[OA\Property(property: "endentdate", type: "string", pattern: "^\\d{2}/\\d{2}$", description: "Day and month numbers of the right boundary")]
    public string $endentdate;
    #[OA\Property(property: "weekly_duration", type: "string", description: "Approximate duration of work per week (in minutes)")]
    public string $weekly_duration;
    #[OA\Property(property: "daily_duration", type: "string", description: "Approximate duration of work per day and (in minutes)")]
    public string $daily_duration;
    #[OA\Property(property: "default_leave_type", type: "string", description: "default leave type for the contract (overwrite default type set in config file).")]
    public string $default_leave_type;
}
