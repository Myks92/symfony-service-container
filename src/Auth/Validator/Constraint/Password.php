<?php

declare(strict_types=1);

namespace App\Auth\Validator\Constraint;

use Attribute;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[Attribute]
final class Password extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(max: 6),
            new Assert\NotCompromisedPassword(),
        ];
    }
}
