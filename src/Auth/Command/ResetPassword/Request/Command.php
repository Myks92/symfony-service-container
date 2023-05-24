<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Shared\Bus\Command\CommandInterface;
use App\Shared\Validator\Constraint\EmailNotBlank;

final readonly class Command implements CommandInterface
{
    /**
     * @param non-empty-string $email
     */
    public function __construct(
        #[EmailNotBlank]
        public string $email
    ) {
    }
}
