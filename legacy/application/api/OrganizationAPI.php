<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api;

use OpenApi\Attributes as OA;

class OrganizationAPI
{

    #[OA\Get(
        path: "/api/entities/{entity_id}/employees/{include_children}",
        summary: "Get employees by entity",
        description: "Returns a list of all employees attached to a specific entity (department/organization), with an option to include sub-entities.",
        tags: ["Departments"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "entity_id",
                description: "Identifier of the entity",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "include_children",
                description: "Include employees from sub-entities (TRUE/FALSE)",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "boolean")
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "List of users attached to the entity",
        content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/User"))
    )]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 404, description: "Entity not found")]
    public function getListOfEmployeesInEntity(int $entityId, bool $children): void
    {
    }
}
