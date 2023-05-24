<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use App\Auth\Validator\Constraint\Password;
use App\Shared\Bus\Command\CommandInterface;
use App\Shared\Validator\Constraint\UuidNotBlank;

final readonly class Command implements CommandInterface
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $current
     * @param non-empty-string $new
     */
    public function __construct(
        #[UuidNotBlank]
        public string $id,
        #[Password]
        public string $current,
        #[Password]
        public string $new,
    ) {
    }
}
