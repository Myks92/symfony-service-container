<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Token;
use App\Shared\Identifier\IdentifierGeneratorInterface;
use DateInterval;
use DateTimeImmutable;

final readonly class Tokenizer
{
    public function __construct(
        private IdentifierGeneratorInterface $randomizer,
        private DateInterval $interval,
    ) {
    }

    public function generate(DateTimeImmutable $date): Token
    {
        return new Token(
            $this->randomizer->generate(),
            $date->add($this->interval)
        );
    }
}
