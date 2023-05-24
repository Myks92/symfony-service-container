<?php

declare(strict_types=1);

namespace App\Auth\Query\FindIdByCredentials;

use App\Auth\Entity\User\Status;
use App\Auth\Service\PasswordHasher;
use App\Shared\Bus\Query\Attribute\QueryHandler;
use Doctrine\DBAL\Connection;

#[QueryHandler]
final readonly class Handler
{
    public function __construct(
        private Connection $connection,
        private PasswordHasher $hasher
    ) {
    }

    public function __invoke(Query $query): ?Result
    {
        $result = $this->connection->createQueryBuilder()
            ->select([
                'id',
                'status',
                'password_hash',
            ])
            ->from('auth_users')
            ->where('email = :email')
            ->setParameter('email', mb_strtolower($query->email))
            ->executeQuery();

        /**
         * @var array{
         *     id: string,
         *     status: string,
         *     password_hash: ?string,
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        $hash = $row['password_hash'];

        if ($hash === null) {
            return null;
        }

        if (!$this->hasher->validate($query->password, $hash)) {
            return null;
        }

        return new Result(
            id: $row['id'],
            isActive: $row['status'] === Status::ACTIVE
        );
    }
}
