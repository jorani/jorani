<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;
use Jorani\Api\Entities\Counter;

#[OA\Schema]
class LeavesSummary
{
    /**
     * @var array<Counter> Comments added on the leave request
     */
    #[OA\Property(
        property: "counters",
        description: "Summary of leaves counter",
        type: "array",
        items: new OA\Items(ref: '#/components/schemas/Counter')
    )]
    public array $counters;
}
