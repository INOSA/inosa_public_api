<?php

declare(strict_types=1);

namespace App\Shared\Application\MessageBus;

interface MessageBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
