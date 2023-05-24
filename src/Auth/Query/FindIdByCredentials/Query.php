<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByCredentials;

use App\Auth\Validator\Constraint\Password;
use App\Shared\Bus\Query\QueryInterface;
use Symfony\Component\Validator\Constraints\Email;

final class Query implements QueryInterface
{
    public function __construct(
        #[Email]
        public string $email = '',
        #[Password]
        public string $password = '',
    ) {
    }
}
