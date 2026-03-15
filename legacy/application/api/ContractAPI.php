<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api;

use OpenApi\Attributes as OA;

class ContractAPI
{
    /**
     * Get all contracts
     */
    #[OA\Get(
        path: "/api/contracts/",
        description: "Get the list of contracts",
        tags: ["Contracts"],
        security: [['jorani_auth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Array of contracts',
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Contract")
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Not Found")
        ]
    )]
    #[OA\Get(
        path: "/api/contracts/{contract_id}",
        description: "Get a specific contract",
        tags: ["Contracts"],
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
                description: "Contract",
                content: new OA\JsonContent(ref: "#/components/schemas/Contract")
            ),
            new OA\Response(response: 401, description: "Unauthorized or not authenticated"),
            new OA\Response(response: 404, description: "Contract not found")
        ]
    )]
    public function getContracts(int $contractId = 0): void
    {
    }

}
