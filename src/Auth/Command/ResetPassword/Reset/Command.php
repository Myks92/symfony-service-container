<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use App\Auth\Validator\Constraint\Password;
use App\Shared\Bus\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command implements CommandInterface
{
    /**
     * @param non-empty-string $token
     * @param non-empty-string $password
     */
    public function __construct(
        #[Assert\NotBlank]
        public string $token,
        #[Password]
        public string $password
    ) {
    }
}
