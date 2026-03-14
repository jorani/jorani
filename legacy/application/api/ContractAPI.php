<?php

namespace Jorani\Api;

use OpenApi\Attributes as OA;
use Jorani\Api\Entities\Contract;


class ContractAPI
{
    /**
     * Get all contracts
     */
    #[OA\Get(
        path: "/api/contracts/",
        description: "Get the list of contracts",
        security: [
            [
                'jorani_auth' => []
            ]
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/Contract")
        ),
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized or not authenticated',
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
    )]
    public function getContracts(): void
    {
    }
}
