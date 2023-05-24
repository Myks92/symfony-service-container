<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeRole;

use App\Auth\Validator\Constraint\Role;
use App\Shared\Bus\Command\CommandInterface;
use App\Shared\Validator\Constraint\UuidNotBlank;

final readonly class Command implements CommandInterface
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $role
     */
    public function __construct(
        #[UuidNotBlank]
        public string $id,
        #[Role]
        public string $role,
    ) {
    }
}
