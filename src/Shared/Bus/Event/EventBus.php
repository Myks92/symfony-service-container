<?php

declare(strict_types=1);

namespace App\Shared\Bus\Event;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class EventBus implements EventBusInterface
{
    public function dispatch(EventInterface $event, array $metadata = []): void
    {
    }
}
