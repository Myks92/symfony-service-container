<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

use App\Shared\Bus\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command implements CommandInterface
{
    /**
     * @param non-empty-string $token
     */
    public function __construct(
        #[Assert\NotBlank]
        public string $token
    ) {
    }
}
