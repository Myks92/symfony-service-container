<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Name;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\Phone;
use App\Auth\Entity\User\Role;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(User::class)]
final class JoinByNetworkTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::joinByNetwork(
            $id = new Id('00000000-0000-0000-0000-000000000001'),
            $date = new DateTimeImmutable(),
            $name = new Name('Fist name', 'Last name'),
            $email = new Email('email@app.test'),
            $network = new Network('vk', '0000001'),
            $phone = new Phone(7, '9124447799'),
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getCreateDate());
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($phone, $user->getPhone());

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertEquals(Role::USER, $user->getRole()->getName());

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertEquals($network, $networks[0] ?? null);
    }
}
