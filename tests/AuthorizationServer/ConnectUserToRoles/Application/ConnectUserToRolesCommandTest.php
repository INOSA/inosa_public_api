<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ConnectUserToRoles\Application;

use App\AuthorizationServer\ConnectUserToRoles\Application\Command\ConnectUserToRolesCommand;
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

final class ConnectUserToRolesCommandTest extends IntegrationTestCase
{
    private MessageBusInterface $commandBus;
    private JsonEncoderInterface $jsonEncoder;
    private MockHttpClient $httpClient;
    private TransportInterface $transport;

    public function testCreateUserCommandWithCorrectCommandWillConnectUserToRole(): void
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

    public function testCreateUserCommandWithCorrectCommandWillAllowToSendEmptyRolesArray(): void
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
            new ConnectUserToRolesCommand(
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
