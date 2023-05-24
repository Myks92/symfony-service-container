<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

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
        $user = $this->users->findByNewEmailToken($command->token);

        if ($user === null) {
            throw new DomainException('Incorrect token.');
        }

        $user->confirmEmailChanging(
            $command->token,
            new DateTimeImmutable()
        );

        $this->flusher->flush($user);
    }
}
