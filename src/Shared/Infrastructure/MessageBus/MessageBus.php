<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\MessageBus;

use App\Shared\Application\MessageBus\CommandInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use Symfony\Component\Messenger\MessageBusInterface as SymfonyMessageBusInterface;

final class MessageBus implements MessageBusInterface
{
    public function __construct(private SymfonyMessageBusInterface $messageBus)
    {
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
