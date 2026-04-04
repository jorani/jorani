<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api;

use OpenApi\Attributes as OA;

class PositionAPI
{
    /**
     * Get the list of positions
     * Useful to get the labels into a cache
     */
    #[OA\Get(
        path: "/api/positions",
        summary: "List all positions",
        description: "Get the list of positions (useful to get the labels into a cache)",
        tags: ["Positions"],
        security: [['jorani_auth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of positions",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Position")
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated")
        ]
    )]
    public function positions(): void
    {
    }
}