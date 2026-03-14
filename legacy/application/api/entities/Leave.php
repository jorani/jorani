<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;
use Jorani\Api\Entities\Comment;
use Jorani\Api\Entities\LeaveStatus;

#[OA\Schema]
class Leave
{
    #[OA\Property(property: "id", type: "integer", description: "Unique identifier of the leave request")]
    public int $id;
    #[OA\Property(property: "startdate", type: "string", format: "date", description: "Start date of the leave request (YYYY-MM-DD)")]
    public string $startdate;
    #[OA\Property(property: "enddate", type: "string", format: "date", description: "End date of the leave request (YYYY-MM-DD)")]
    public string $enddate;
    #[OA\Property(property: "status", type: "integer", description: "Identifier of the status of the leave request (Requested, Accepted, etc.). See status table.")]
    public LeaveStatus $status;
    #[OA\Property(property: "employee", type: "integer", description: "Employee requesting the leave request")]
    public int $employee;
    #[OA\Property(property: "cause", type: "string", description: "Reason of the leave request")]
    public string $cause;
    #[OA\Property(property: "startdatetype", type: "string", description: "Morning/Afternoon")]
    public string $startdatetype;
    #[OA\Property(property: "enddatetype", type: "string", description: "Morning/Afternoon")]
    public string $enddatetype;
    #[OA\Property(property: "duration", type: "integer", description: "Length of the leave request")]
    public int $duration;
    #[OA\Property(property: "type", type: "integer", description: "Identifier of the type of the leave request (Paid, Sick, etc.). See type table.")]
    public int $type;

    /**
     * @var array<Comment> Comments added on the leave request
     */
    #[OA\Property(
        property: "comments",
        description: "Comments on leave request (JSON)",
        type: "array",
        items: new OA\Items(ref: "#/components/schemas/Comment")
    )]
    public array $comments;
    #[OA\Property(property: "document", type: "string", description: "Optional supporting document")]
    public string $document;
}

