<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;
use Jorani\Api\Entities\Comment;
use Jorani\Api\Entities\LeaveStatus;

#[OA\Schema]
class Leave
{
    #[OA\Property(property: "id", description: "Unique identifier of the leave request")]
    public int $id;
    #[OA\Property(property: "startdate", format: "date", description: "Start date of the leave request (YYYY-MM-DD)")]
    public string $startdate;
    #[OA\Property(property: "enddate", format: "date", description: "End date of the leave request (YYYY-MM-DD)")]
    public string $enddate;
    #[OA\Property(property: "status", ref: "#/components/schemas/LeaveStatus", description: "Status of the leave request")]
    public LeaveStatus $status;
    #[OA\Property(property: "employee", description: "Employee requesting the leave request")]
    public int $employee;
    #[OA\Property(property: "cause", description: "Reason of the leave request")]
    public string $cause;
    #[OA\Property(property: "startdatetype", enum: ["Morning", "Afternoon"], description: "Morning/Afternoon")]
    public string $startdatetype;
    #[OA\Property(property: "enddatetype", enum: ["Morning", "Afternoon"], description: "Morning/Afternoon")]
    public string $enddatetype;
    #[OA\Property(property: "duration", description: "Length of the leave request")]
    public float $duration;
    #[OA\Property(property: "type", description: "Type of the leave request (Paid, Sick, etc.). See type table.")]
    public int $type;
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

