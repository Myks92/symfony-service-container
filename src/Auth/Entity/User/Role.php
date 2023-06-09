<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Shared\Assert;

final readonly class Role
{
    public const USER = 'user';
    public const ADMIN = 'admin';

    private string $name;

    /**
     * @param non-empty-string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN,
        ]);

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
