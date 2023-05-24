<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Id;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Id::class)]
final class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = '00000000-0000-0000-0000-000000000001');

        self::assertEquals($value, $id->getValue());
    }

    public function testCase(): void
    {
        $value = '00000000-0000-0000-0000-000000000001';

        $id = new Id(mb_strtoupper($value));

        self::assertEquals($value, $id->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Id('12345');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Id('');
    }
}
