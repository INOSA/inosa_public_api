<?php

declare(strict_types=1);

namespace App\Shared\Application\MessageBus;

use App\Shared\Application\CommandHandlerException;

interface MessageBusInterface
{
    /**
     * @throws CommandHandlerException
     */
    public function dispatch(CommandInterface $command): void;
}
