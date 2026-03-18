<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api;

use OpenApi\Attributes as OA;

class LeaveAPI
{

    /**
     * Get the leaves counter of a given employee
     * * @param int $employeeId Unique identifier of an employee
     * @param string|null $refTmp Date of reference (YYYY-MM-DD or Unix timestamp)
     */
    #[OA\Get(
        path: "/api/leavessummary/{employee_id}",
        description: "Get the leaves counter of a given employee for the current date",
        tags: ["Leaves"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "employee_id",
                description: "Identifier of the employee",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Get the leaves counter of a given employee",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/LeavesSummary")
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Employee not found")
        ]
    )]
    #[OA\Get(
        path: "/api/leavessummary/{employee_id}/{refTmp}",
        description: "Get the leaves counter of a given employee for a specific reference date",
        tags: ["Leaves"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "employee_id",
                description: "Identifier of the employee",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "refTmp",
                description: "Reference date (YYYY-MM-DD or Unix timestamp)",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Leaves counter",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/LeavesSummary")
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Employee not found"),
            new OA\Response(response: 422, description: "Invalid parameters")
        ]
    )]
    public function leavessummary(int $employeeId, ?string $refTmp = null): void
    {
    }

    #[OA\Get(
        path: "/api/leaves/{start_date}/{end_date}",
        description: "Get all the leave requests stored into the database within the specified date range",
        tags: ["Leaves"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "start_date",
                description: "Start date (YYYY-MM-DD or Unix timestamp)",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", format: "date")
            ),
            new OA\Parameter(
                name: "end_date",
                description: "End date (YYYY-MM-DD or Unix timestamp)",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", format: "date")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of leave requests",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Leave")
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 422, description: "Invalid parameters")
        ]
    )]
    public function leavesInRange(string $startDate, string $endDate): void
    {
    }

    #[OA\Get(
        path: "/api/acceptleave/{leave_id}",
        description: "Accept a leave request and update its status to Accepted",
        tags: ["Leaves"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "leave_id",
                description: "Identifier of the leave request",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Leave request accepted"),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Leave request not found")
        ]
    )]
    public function acceptleave(int $leaveId): void
    {
    }

    #[OA\Get(
        path: "/api/rejectleave/{leave_id}",
        description: "Reject a leave request and update its status to Rejected",
        tags: ["Leaves"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "leave_id",
                description: "Identifier of the leave request",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Leave request rejected"),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Leave request not found")
        ]
    )]
    public function rejectleave(int $leaveId): void
    {
    }


    #[OA\Post(
        path: "/api/leaves",
        description: "Create a new leave request in the database. This endpoint is typically used for imposed leaves and does not trigger email notifications.",
        tags: ["Leaves"],
        security: [['jorani_auth' => []]],
        requestBody: new OA\RequestBody(
            description: "Leave Request Data",
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["startdate", "enddate", "employee", "type"], // Liste des champs obligatoires
                    properties: [
                        new OA\Property(property: "startdate", type: "string", format: "date", description: "Start date of the leave request"),
                        new OA\Property(property: "enddate", type: "string", format: "date", description: "End date of the leave request"),
                        new OA\Property(property: "status", type: "integer", description: "Status ID. See LeaveStatus enum.", enum: [1, 2, 3, 4, 5]),
                        new OA\Property(property: "employee", type: "integer", description: "Employee ID requesting the leave"),
                        new OA\Property(property: "cause", type: "string", description: "Reason for the request"),
                        new OA\Property(property: "startdatetype", type: "string", description: "Morning or Afternoon", pattern: "^(Morning|Afternoon)$"),
                        new OA\Property(property: "enddatetype", type: "string", description: "Morning or Afternoon", pattern: "^(Morning|Afternoon)$"),
                        new OA\Property(property: "duration", type: "integer", description: "Length of the leave request in days"),
                        new OA\Property(property: "type", type: "integer", description: "Leave type ID (Paid, Sick, etc.)"),
                        new OA\Property(property: "comments", type: "string", description: "Comments in JSON format"),
                        new OA\Property(property: "document", type: "string", description: "Optional supporting document (link or identifier)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Leave is created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", description: "The newly created leave request ID")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Employee or leave type not found"),
            new OA\Response(response: 422, description: "Missing mandatory fields or invalid parameters")
        ]
    )]
    public function createleave(): void
    {
    }
}