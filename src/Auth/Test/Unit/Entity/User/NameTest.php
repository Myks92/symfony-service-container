<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Name;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Name::class)]
final class NameTest extends TestCase
{
    public function testSuccess(): void
    {
        $name = new Name($first = 'First', $last = 'Last');

        self::assertEquals($first, $name->getFirst());
        self::assertEquals($last, $name->getLast());
        self::assertEquals($first . ' ' . $last, $name->getFull());
    }

    public function testFirstEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Name('', 'Last');
    }

    public function testLastEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Name('First', '');
    }

    public function testUpperFirstFormLowerName(): void
    {
        $name = new Name('first', 'last');

        self::assertEquals('First', $name->getFirst());
        self::assertEquals('Last', $name->getLast());
    }

    public function testUpperFirstFormUpperName(): void
    {
        $name = new Name('FIRST', 'LAST');

        self::assertEquals('First', $name->getFirst());
        self::assertEquals('Last', $name->getLast());
    }

    public function testTrim(): void
    {
        $name = new Name('First ', 'Last ');

        self::assertEquals('First', $name->getFirst());
        self::assertEquals('Last', $name->getLast());
    }
}
