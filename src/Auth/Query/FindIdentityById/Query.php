<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdentityById;

use App\Shared\Bus\Query\QueryInterface;
use App\Shared\Validator\Constraint\UuidNotBlank;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Query implements QueryInterface
{
    public function __construct(
        #[UuidNotBlank]
        public string $id = '',
    ) {
    }
}
