<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Overtime
{
    #[OA\Property(property: "id", type: "integer", description: "Unique identifier of the overtime request")]
    public int $id;
    #[OA\Property(property: "employee", type: "integer", description: "Employee requesting the OT")]
    public int $employee;
    #[OA\Property(property: "date", format: "date", type: "string", description: "Date when the OT was done (YYYY-MM-DD)")]
    public string $date;
    #[OA\Property(property: "duration", type: "number", description: "Duration of the OT")]
    public float $duration;
    #[OA\Property(property: "cause", type: "string", description: "Reason why the OT was done")]
    public string $cause;
    #[OA\Property(property: "status", type: "integer", description: "Status of OT (Planned, Requested, Accepted, Rejected)")]
    public int $status;
}
