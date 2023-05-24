<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

use App\Shared\Bus\Command\CommandInterface;
use App\Shared\Validator\Constraint\UuidNotBlank;

final readonly class Command implements CommandInterface
{
    /**
     * @param non-empty-string $id
     */
    public function __construct(
        #[UuidNotBlank]
        public string $id
    ) {
    }
}
