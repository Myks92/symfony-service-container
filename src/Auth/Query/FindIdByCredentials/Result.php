<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByCredentials;

final readonly class Result
{
    public function __construct(
        public string $id,
        public bool $isActive,
    ) {
    }
}
