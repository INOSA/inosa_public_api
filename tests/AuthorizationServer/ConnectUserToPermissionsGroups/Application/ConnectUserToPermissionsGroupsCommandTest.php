<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ConnectUserToPermissionsGroups\Application;

use App\AuthorizationServer\ConnectUserToPermissionsGroups\Application\Command\ConnectUserToPermissionsGroupsCommand;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayList;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class ConnectUserToPermissionsGroupsCommandTest extends IntegrationTestCase
{
    private MessageBusInterface $commandBus;
    private TransportInterface $transport;

    public function testConnectUserToPermissionsGroupsCommandWithCorrectCommandWillDispatchCommand(): void
    {
        $this->commandBus->dispatch(
            new ConnectUserToPermissionsGroupsCommand(
                new UserIdentifier('e718bb65-f9d0-4c7e-ab61-327e4e51149b'),
                ArrayList::create(
                    [
                        '573f467d-e847-4fcd-8fd0-cfe8b817eda4',
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
