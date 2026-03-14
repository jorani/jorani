<?php

namespace Jorani\Api\Entities;

use OpenApi\Attributes as OA;

#[OA\Schema]
class MonthlyPresence
{
    #[OA\Property(property: "leaves", type: "number", description: "Number of leave taken")]
    public float $leaves;
    #[OA\Property(property: "dayoffs", type: "number", description: "Number of non working days")]
    public float $dayoffs;
    #[OA\Property(property: "total", type: "number", description: "Total number of days in the month")]
    public float $total;
    #[OA\Property(property: "start", type: "string", description: "First day of the month (YYYY-MM-DD)")]
    public string $start;
    #[OA\Property(property: "end", type: "string", description: "Last day of the month (YYYY-MM-DD)")]
    public string $end;
    #[OA\Property(property: "open", type: "number", description: "Number of opened days (Total - Days off)")]
    public float $open;
    #[OA\Property(property: "work", type: "number", description: "Number of worked days (Total - Days off - Leaves)")]
    public float $work;
}
