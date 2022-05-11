<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ArchiveUser\Application;

use App\AuthorizationServer\ArchiveUser\Application\Command\ArchiveUserCommand;
use App\AuthorizationServer\ArchiveUser\Domain\KeepRole;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Tests\IntegrationTestCase;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class ArchiveUserCommandTest extends IntegrationTestCase
{
    private MessageBusInterface $commandBus;
    private TransportInterface $transport;

    public function testArchiveUserCommandWithCorrectCommandWillDispatchCommand(): void
    {
        $this->commandBus->dispatch(
            new ArchiveUserCommand(
                new UserIdentifier('e718bb65-f9d0-4c7e-ab61-327e4e51149b'),
                new KeepRole(true),
                null,
                null,
                null,
                null,
            )
        );

        self::assertCount(1, $this->transport->get());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->commandBus = $container->get(MessageBusInterface::class);
        $this->transport = $container->get('messenger.transport.sync');
    }
}
