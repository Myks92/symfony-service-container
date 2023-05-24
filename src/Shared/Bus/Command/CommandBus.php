<?php

declare(strict_types=1);

namespace App\Shared\Bus\Command;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class CommandBus implements CommandBusInterface
{
    public function dispatch(CommandInterface $command, array $metadata = []): void
    {
    }
}
