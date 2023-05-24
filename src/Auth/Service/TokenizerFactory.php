<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Shared\Identifier\IdentifierGeneratorInterface;
use DateInterval;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class TokenizerFactory
{
    public function __construct(
        private IdentifierGeneratorInterface $randomizer
    ) {
    }

    public function __invoke(string $interval): Tokenizer
    {
        return new Tokenizer(
            $this->randomizer,
            new DateInterval($interval)
        );
    }
}
