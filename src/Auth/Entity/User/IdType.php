<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Shared\Aggregate\AbstractAggregateId;

final class IdType extends AbstractAggregateId
{
    public const NAME = 'auth_user_id';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getClassName(): string
    {
        return Id::class;
    }
}
