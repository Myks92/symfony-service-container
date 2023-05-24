<?php

declare(strict_types=1);

namespace App\Auth\Command\DetachNetwork;

use App\Auth\Validator\Constraint\Network;
use App\Shared\Bus\Command\CommandInterface;
use App\Shared\Validator\Constraint\UuidNotBlank;

final readonly class Command implements CommandInterface
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $network
     * @param non-empty-string $identity
     */
    public function __construct(
        #[UuidNotBlank]
        public string $id,
        #[Network]
        public string $network,
        #[Network]
        public string $identity,
    ) {
    }
}
