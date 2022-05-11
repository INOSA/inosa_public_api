<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ConnectUserToRoles\Application;

use App\AuthorizationServer\ConnectUserToRoles\Application\Command\ConnectUserToRolesCommand;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayList;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class ConnectUserToRolesCommandTest extends IntegrationTestCase
{
    private MessageBusInterface $commandBus;
    private TransportInterface $transport;

    public function testConnectUserToRolesCommandWithCorrectCommandWillDispatchCommand(): void
    {
        $this->commandBus->dispatch(
            new ConnectUserToRolesCommand(
                new UserIdentifier('827e5b3b-780d-49aa-a4e7-af29a328736b'),
                ArrayList::create(
                    [
                        '190af733-e90a-4222-9230-9b321cb763c6',
                    ],
                ),
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
