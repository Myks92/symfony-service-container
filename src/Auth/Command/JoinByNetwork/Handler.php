<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Name;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\Phone;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Shared\Bus\Command\Attribute\CommandHandler;
use App\Shared\Flusher\FlusherInterface;
use DateTimeImmutable;
use DomainException;

#[CommandHandler]
final readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private FlusherInterface $flusher
    ) {
    }

    public function __invoke(Command $command): void
    {
        $network = new Network($command->network, $command->identity);
        $email = new Email($command->email);
        $phone = Phone::fromString($command->phone);

        if ($this->users->hasByNetwork($network)) {
            throw new DomainException('User with this network already exists.');
        }

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('User with this email already exists.');
        }

        if ($this->users->hasByPhone($phone)) {
            throw new DomainException('User with this phone already exists.');
        }

        $user = User::joinByNetwork(
            new Id($command->id),
            new DateTimeImmutable(),
            new Name($command->firstName, $command->lastName),
            $phone,
            $email,
            $network
        );

        $this->users->add($user);

        $this->flusher->flush($user);
    }
}
