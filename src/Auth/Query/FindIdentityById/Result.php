<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdentityById;

final class Result
{
    public function __construct(
        public readonly string $id,
        public readonly string $lastName,
        public readonly string $firstName,
        public readonly ?string $phone,
        public readonly string $email,
        public readonly string $role,
    ) {
    }
}
