<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\MessageBus;

use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\MessageBus\CommandInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use LogicException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface as SymfonyMessageBusInterface;

final class MessageBus implements MessageBusInterface
{
    public function __construct(private SymfonyMessageBusInterface $messageBus)
    {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);

            return;
        } catch (HandlerFailedException $exception) {
            if (null !== $exception->getPrevious()) {
                throw new CommandHandlerException(
                    $exception->getPrevious()->getMessage(),
                    $exception->getPrevious()->getCode()
                );
            }
        }

        throw new LogicException(
            sprintf(
                'Handler for command %s failed with exception %s and message %s',
                $command::class,
                $exception::class,
                $exception->getMessage(),
            )
        );
    }
}
