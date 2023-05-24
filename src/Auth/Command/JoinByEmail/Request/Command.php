<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Validator\Constraint\Password;
use App\Shared\Bus\Command\CommandInterface;
use App\Shared\Validator\Constraint\EmailNotBlank;
use App\Shared\Validator\Constraint\UuidNotBlank;
use Symfony\Component\Validator\Constraints as Assert;

final class Command implements CommandInterface
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $email
     * @param non-empty-string $password
     */
    public function __construct(
        #[UuidNotBlank]
        public readonly string $id,
        #[Assert\NotBlank]
        public string $firstName,
        #[Assert\NotBlank]
        public string $lastName,
        #[EmailNotBlank]
        public readonly string $email,
        #[Password]
        public readonly string $password,
    ) {
    }
}
