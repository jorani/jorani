<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Position
{
    #[OA\Property(property: "id", type: "integer", description: "Unique identifier of the position")]
    public int $id;
    #[OA\Property(property: "name", type: "string", description: "Name of the position")]
    public string $name;
    #[OA\Property(property: "description", type: "string", description: "Description of the position")]
    public string $description;
}
