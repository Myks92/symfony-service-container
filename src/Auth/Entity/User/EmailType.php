<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Shared\Doctrine\Types\AbstractEmailType;

final class EmailType extends AbstractEmailType
{
    public const NAME = 'auth_user_email';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getClassName(): string
    {
        return Email::class;
    }
}
