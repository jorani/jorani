<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api;

use OpenApi\Attributes as OA;

class EntitledDayAPI
{
    #[OA\Get(
        path: "/api/entitleddayscontract/{contract_id}",
        description: "Get the list of entitled days for a given contract",
        tags: ["Entitled Days"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "contract_id",
                description: "Identifier of the contract",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of entitled days",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/EntitledDay")
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Contract not found")
        ]
    )]
    public function getEntitledDaysByContract(int $contractId): void
    {
    }

    #[OA\Post(
        path: "/api/addentitleddayscontract/{contract_id}",
        description: "Add entitlement to a contract",
        tags: ["Entitled Days"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "contract_id",
                description: "Identifier of the contract",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            description: "Entitlement to be added to a contract",
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "startdate", type: "string", format: "date", description: "Left boundary of the credit validity (YYYY-MM-DD)"),
                        new OA\Property(property: "enddate", type: "string", format: "date", description: "Right boundary of the credit validity. Duration cannot exceed one year (YYYY-MM-DD)"),
                        new OA\Property(property: "type", type: "integer", description: "Leave type"),
                        new OA\Property(property: "days", type: "number", format: "float", description: "Number of days (can be negative)"),
                        new OA\Property(property: "description", type: "string", description: "Description of a credit / debit")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "The entitlement was added on the contract",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", description: "Unique identifier of an entitlement")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Contract not found"),
            new OA\Response(response: 422, description: "Invalid parameters")
        ]
    )]
    public function addentitleddayscontract(int $contractId): void
    {
    }

    #[OA\Get(
        path: "/api/entitleddaysemployee/{employee_id}",
        description: "Get the list of entitled days for a given employee",
        tags: ["Entitled Days"],
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
                description: "List of entitled days",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/EntitledDay")
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized or not authenticated"
            ),
            new OA\Response(
                response: 404,
                description: "Employee not found"
            )
        ]
    )]
    public function entitleddaysemployee(int $employeeId): void
    {
    }


    #[OA\Post(
        path: "/api/addentitleddaysemployee/{employee_id}",
        description: "Give entitlement to an employee",
        tags: ["Entitled Days"],
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
        requestBody: new OA\RequestBody(
            description: "Entitlement to be added to an employee",
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "startdate", type: "string", format: "date", description: "Left boundary of the credit validity (YYYY-MM-DD)"),
                        new OA\Property(property: "enddate", type: "string", format: "date", description: "Right boundary of the credit validity. Duration cannot exceed one year (YYYY-MM-DD)"),
                        new OA\Property(property: "type", type: "integer", description: "Leave type"),
                        new OA\Property(property: "days", type: "number", format: "float", description: "Number of days (can be negative so as to deduct/adjust entitlement)"),
                        new OA\Property(property: "description", type: "string", description: "Description of a credit / debit (entitlement / adjustment)")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "The entitlement was added on the employee",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", description: "Unique identifier of an entitlement")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Employee not found"),
            new OA\Response(response: 422, description: "Invalid parameters")
        ]
    )]
    public function addentitleddaysemployee(int $employeeId): void
    {
    }
}
