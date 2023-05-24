<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\Tokenizer;
use App\Shared\Identifier\IdentifierGeneratorInterface;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Auth\Service\Tokenizer
 *
 * @internal
 */
final class TokenizerTest extends TestCase
{
    public function testSuccess(): void
    {
        $randomizer = $this->createMock(IdentifierGeneratorInterface::class);
        $randomizer->expects(self::once())->method('generate')
            ->willReturn($value = '00000000-0000-0000-0000-000000000001');

        $interval = new DateInterval('PT1H');
        $date = new DateTimeImmutable('+1 day');

        $tokenizer = new Tokenizer($randomizer, $interval);

        $token = $tokenizer->generate($date);

        self::assertEquals($value, $token->getValue());
        self::assertEquals($date->add($interval), $token->getExpires());
    }
}
