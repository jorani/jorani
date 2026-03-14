<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Department
{
    #[OA\Property(property: "id", type: "integer", description: "Unique identifier of the department")]
    public int $id;
    #[OA\Property(property: "name", type: "string", description: "Name of the department")]
    public string $name;
    #[OA\Property(property: "parent_id", type: "integer", description: "Parent department (or -1 if root)")]
    public int $parent_id;
    #[OA\Property(property: "supervisor", type: "integer", description: "This user will receive a copy of accepted and rejected leave requests")]
    public int $supervisor;
}
