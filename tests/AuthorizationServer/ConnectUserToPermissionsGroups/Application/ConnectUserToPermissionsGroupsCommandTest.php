<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ConnectUserToPermissionsGroups\Application;

use App\AuthorizationServer\ConnectUserToPermissionsGroups\Application\Command\ConnectUsersToPermissionsGroupsCommand;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ConnectUserToPermissionsGroupsCommandTest extends IntegrationTestCase
{
    private MessageBusInterface $commandBus;
    private JsonEncoderInterface $jsonEncoder;
    private MockHttpClient $httpClient;
    private TransportInterface $transport;

    public function testConnectUserToPermissionsGroupsCommandWithCorrectCommandWillConnectUserToPermissionsGroups(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => [],
                                'errors' => [],
                            ],
                        )
                    ),
                    [
                        'http_code' => 204,
                    ],
                ),
            ],
        );

        $this->commandBus->dispatch(
            new ConnectUsersToPermissionsGroupsCommand(
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

    public function testConnectUserToPermissionsGroupsCommandWithCorrectCommandWillAllowToSendEmptyPermissionsGroupsArray(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => [],
                                'errors' => [],
                            ],
                        )
                    ),
                    [
                        'http_code' => 204,
                    ],
                ),
            ],
        );

        $this->commandBus->dispatch(
            new ConnectUsersToPermissionsGroupsCommand(
                new UserIdentifier('827e5b3b-780d-49aa-a4e7-af29a328736b'),
                ArrayList::create([]),
            )
        );

        self::assertCount(1, $this->transport->get());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->httpClient = $container->get(HttpClientInterface::class);
        $this->commandBus = $container->get(MessageBusInterface::class);
        $this->jsonEncoder = $container->get(JsonEncoderInterface::class);
        $this->transport = $container->get('messenger.transport.sync');
    }
}
