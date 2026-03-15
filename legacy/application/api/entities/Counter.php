<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Counter
{
    #[OA\Property(property: "entitled", type: "number", description: "Ent")]
    public float $entitled;
    #[OA\Property(property: "taken", type: "number", description: "Taken")]
    public float $taken;
    #[OA\Property(property: "left", type: "string", description: "Taken")]
    public string $left;
    #[OA\Property(property: "misc", type: "string", description: "Misc")]
    public string $misc;
}
