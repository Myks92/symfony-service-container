<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Shared\Bus\Command\Attribute\CommandHandler;
use App\Shared\Flusher\FlusherInterface;

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
        $user = $this->users->get(new Id($command->id));

        $user->remove();

        $this->users->remove($user);

        $this->flusher->flush($user);
    }
}
