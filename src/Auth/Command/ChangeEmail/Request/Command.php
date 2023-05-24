<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

use App\Shared\Bus\Command\CommandInterface;
use App\Shared\Validator\Constraint\EmailNotBlank;
use App\Shared\Validator\Constraint\UuidNotBlank;

final readonly class Command implements CommandInterface
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $email
     */
    public function __construct(
        #[UuidNotBlank]
        public string $id,
        #[EmailNotBlank]
        public string $email,
    ) {
    }
}
