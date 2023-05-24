<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\Tokenizer;
use App\Shared\Bus\Command\Attribute\CommandHandler;
use App\Shared\Flusher\FlusherInterface;
use DateTimeImmutable;

#[CommandHandler]
final readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private Tokenizer $tokenizer,
        private FlusherInterface $flusher,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $email = new Email($command->email);

        $user = $this->users->getByEmail($email);

        $date = new DateTimeImmutable();

        $user->requestPasswordReset(
            $this->tokenizer->generate($date),
            $date
        );

        $this->flusher->flush($user);
    }
}
