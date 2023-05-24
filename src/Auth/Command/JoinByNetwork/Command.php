<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

use App\Auth\Validator\Constraint\Network;
use App\Shared\Bus\Command\CommandInterface;
use App\Shared\Validator\Constraint\EmailNotBlank;
use App\Shared\Validator\Constraint\UuidNotBlank;
use Symfony\Component\Validator\Constraints as Assert;

final class Command implements CommandInterface
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $firstName
     * @param non-empty-string $lastName
     * @param non-empty-string $email
     * @param non-empty-string $network
     * @param non-empty-string $identity
     */
    public function __construct(
        #[UuidNotBlank]
        public string $id,
        #[Assert\NotBlank]
        public string $firstName,
        #[Assert\NotBlank]
        public string $lastName,
        #[EmailNotBlank]
        public string $email,
        #[Network]
        public string $network,
        #[Network]
        public string $identity,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $phone = null,
    ) {
    }
}
