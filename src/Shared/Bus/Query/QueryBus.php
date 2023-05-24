<?php

declare(strict_types=1);

namespace App\Shared\Bus\Query;


/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class QueryBus implements QueryBusInterface
{
    public function dispatch(QueryInterface $query, array $metadata = []): mixed
    {
        return '';
    }
}
