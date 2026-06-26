<?php

namespace App\Services\Checkers;

use App\Models\Service;

interface CheckerInterface
{
    public function __construct(Service $service);
    
    /**
     * @return array{status: string, response_time_ms: float|null, message: string|null}
     */
    public function check(): array;
}
