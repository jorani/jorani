<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api;

use OpenApi\Attributes as OA;

class LeaveTypeAPI
{
    #[OA\Get(
        path: "/api/leavetypes/",
        summary: "List all leave types",
        description: "Get the list of leave types (useful to get the labels into a cache)",
        tags: ["Leaves"],
        security: [['jorani_auth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of leave types",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/LeaveType")
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated")
        ]
    )]
    public function leavetypes(): void
    {
    }
}