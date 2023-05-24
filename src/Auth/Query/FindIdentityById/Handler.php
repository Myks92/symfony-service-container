<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdentityById;

use App\Shared\Bus\Query\Attribute\QueryHandler;
use Doctrine\DBAL\Connection;

#[QueryHandler]
final readonly class Handler
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function __invoke(Query $query): ?Result
    {
        $result = $this->connection->createQueryBuilder()
            ->select([
                'id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'role',
            ])
            ->from('auth_users')
            ->where('id = :id')
            ->setParameter('id', $query->id)
            ->executeQuery();

        /** @var array{
         *     id: string,
         *     first_name: string,
         *     last_name: string,
         *     email: string,
         *     phone: ?string,
         *     role: string,
         * }|false $row */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Result(
            id: $row['id'],
            lastName: $row['last_name'],
            firstName: $row['first_name'],
            phone: $row['phone'],
            email: $row['email'],
            role: $row['role'],
        );
    }
}
