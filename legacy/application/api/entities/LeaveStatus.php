<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: "Status of a leave or overtime request",
    type: "integer"
)]
enum LeaveStatus: int
{
    #[OA\Description("Request initially created by the employee")]
    case REQUESTED = 1;

    #[OA\Description("Request validated by the manager")]
    case ACCEPTED = 2;

    #[OA\Description("Request rejected")]
    case REJECTED = 3;

    #[OA\Description("Request pending validation (submitted)")]
    case PLANNED = 4;

    #[OA\Description("Request cancelled by the user")]
    case CANCELLED = 5;
}
