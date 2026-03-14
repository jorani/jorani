<?php

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
class OpenApiSpec
{
}
