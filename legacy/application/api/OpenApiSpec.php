<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Jorani HTTP API',
    description: 'Leave & Overtime Management System',
)]
#[OA\PathItem(path: "/api")]
#[OA\Server(url: API_HOST)]
#[OA\SecurityScheme(
    type: 'oauth2',
    name: 'jorani_auth',
    securityScheme: 'jorani_auth',
    flows: [
        new OA\Flow(
            flow: 'clientCredentials',
            tokenUrl: TOKEN_URL,
            refreshUrl: TOKEN_URL,
            scopes: [
                "users" => "Access to users' information",
            ]
        )
    ]
)]
#[OA\License(name: 'MIT')]
#[OA\Tag(name: "Users", description: "Management of users")]
#[OA\Tag(name: "Positions", description: "Management of positions")]
#[OA\Tag(name: "Contracts", description: "Operations related to employee contracts")]
#[OA\Tag(name: "Entitled Days", description: "Operations related to entitled days")]
#[OA\Tag(name: "Leaves", description: "Management of leave requests")]
#[OA\Tag(name: "Overtime", description: "Management of overtime requests")]
#[OA\Tag(name: "Organization", description: "Management of the organization")]
class OpenApiSpec
{
}
