<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class EntitledDay
{
    #[OA\Property(property: "id", type: "integer", description: "Unique identifier of an entitlement")]
    public int $id;
    #[OA\Property(property: "contract", type: "integer", description: "If entitlement is credited to a contract, Id of contract")]
    public int $contract;
    #[OA\Property(property: "employee", type: "integer", description: "If entitlement is credited to an employee, Id of employee")]
    public int $employee;
    #[OA\Property(property: "overtime", type: "integer", description: "Optional Link to an overtime request, if the credit is due to an OT")]
    public int $overtime;
    #[OA\Property(property: "startdate", type: "string", format: "date", description: "Left boundary of the credit validity (YYYY-MM-DD)")]
    public string $startdate;
    #[OA\Property(property: "enddate", type: "string", format: "date", description: "Right boundary of the credit validity. Duration cannot exceed one year (YYYY-MM-DD)")]
    public string $enddate;
    #[OA\Property(property: "type", type: "integer", description: "Leave type")]
    public int $type;
    #[OA\Property(property: "days", type: "number", description: "Number of days (can be negative so as to deduct/adjust entitlement)")]
    public float $days;
    #[OA\Property(property: "description", type: "string", description: "Description of a credit / debit (entitlement / adjustment)")]
    public string $description;
}
