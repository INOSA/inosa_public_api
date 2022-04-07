<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\CreateUser\Application;

use App\AuthorizationServer\CreateUser\Application\CreateUserCommand;
use App\AuthorizationServer\CreateUser\Domain\User\Email;
use App\AuthorizationServer\CreateUser\Domain\User\FirstName;
use App\AuthorizationServer\CreateUser\Domain\User\LastName;
use App\AuthorizationServer\CreateUser\Domain\User\UserName;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CreateUserCommandTest extends IntegrationTestCase
{
    private MessageBusInterface $commandBus;
    private JsonEncoderInterface $jsonEncoder;
    private MockHttpClient $httpClient;
    private TransportInterface $transport;

    public function testCreateUserCommandWithCorrectCommandWillCreateUser(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => null,
                                'errors' => [],
                            ],
                        )
                    ),
                    [
                        'http_code' => 201,
                    ],
                ),
            ],
        );

        $this->commandBus->dispatch(
            new CreateUserCommand(
                new UserIdentifier('827e5b3b-780d-49aa-a4e7-af29a328736b'),
                new UserName('joey666'),
                new FirstName('joey'),
                new LastName('tribbiani'),
                new Email('joey.tribbiani@inosa.no'),
                ArrayList::create([]),
                new DepartmentIdentifier('9bed3d89-7f95-496c-be5e-ba3819db992a'),
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
