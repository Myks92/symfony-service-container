<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Entity\User\Network;
use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Auth\Entity\User\User
 *
 * @internal
 */
final class DetachNetworkTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withNetwork($vk = new Network('vk', '0000001'))
            ->withNetwork($google = new Network('google', '0000001'))
            ->build();

        $user->detachNetwork($vk);

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertEquals($google, end($networks));
    }

    public function testLastWithoutPassword(): void
    {
        $user = (new UserBuilder())
            ->viaNetwork($network = new Network('vk', '0000001'))
            ->build();

        $this->expectExceptionMessage('Unable to detach the last identity.');
        $user->detachNetwork($network);
    }

    public function testNotFound(): void
    {
        $user = (new UserBuilder())->build();

        $this->expectExceptionMessage('Network is not not found.');
        $user->detachNetwork(new Network('none', '0000001'));
    }
}
