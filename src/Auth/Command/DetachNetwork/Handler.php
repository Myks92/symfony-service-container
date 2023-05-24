<?php

declare(strict_types=1);

namespace App\Auth\Command\DetachNetwork;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\UserRepository;
use App\Shared\Bus\Command\Attribute\CommandHandler;
use App\Shared\Flusher\FlusherInterface;
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

        if (!$this->users->hasByNetwork($network)) {
            throw new DomainException('User with this network not found.');
        }

        $user = $this->users->get(new Id($command->id));

        $user->detachNetwork($network);

        $this->flusher->flush($user);
    }
}
