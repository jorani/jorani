<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Comment
{
    #[OA\Property(property: "type", type: "string", description: "Type of comment (change or comment)")]
    public string $type;
    #[OA\Property(property: "status_number", type: "integer", description: "If comment of type change, new status id")]
    public int $status_number;
    #[OA\Property(property: "date", format: "date", type: "string", description: "Date of the comment")]
    public string $date;
    #[OA\Property(property: "author", type: "integer", description: "Identifier of the employee commenting the request")]
    public int $author;
    #[OA\Property(property: "value", type: "string", description: "If comment of type comment, the content of comment")]
    public string $value;
}