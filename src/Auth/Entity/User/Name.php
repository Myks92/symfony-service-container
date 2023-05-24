<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Shared\Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[ORM\Embeddable]
final readonly class Name
{
    #[ORM\Column(type: 'string')]
    private string $first;
    #[ORM\Column(type: 'string')]
    private string $last;

    /**
     * @param non-empty-string $first
     * @param non-empty-string $last
     */
    public function __construct(
        string $first,
        string $last,
    ) {
        Assert::notEmpty($first);
        Assert::notEmpty($last);
        $this->first = ucfirst(strtolower(trim($first)));
        $this->last = ucfirst(strtolower(trim($last)));
    }

    public function getFirst(): string
    {
        return $this->first;
    }

    public function getLast(): string
    {
        return $this->last;
    }

    public function getFull(): string
    {
        return $this->first . ' ' . $this->last;
    }
}