<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetRoleStatusPerUser\Integration\Application\Query;

use App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query\GetReadingStatusForOneUserQueryInterface;
use App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query\GetReadingStatusForOneUserRequest;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Domain\Identifier\DocumentIdentifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetReadingStatusForOneUserTest extends IntegrationTestCase
{
    private GetReadingStatusForOneUserQueryInterface $query;
    private MockHttpClient $httpClient;
    private JsonEncoderInterface $jsonEncoder;
    private IdentifierFactoryInterface $identifierFactory;

    public function testGetReadingStatusForOneUserTestSuccessResponse(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => [
                                    'response_from_api',
                                ],
                                'errors' => null,
                            ],
                        )
                    )
                ),
            ],
        );

        $response = $this->query->getReadingStatusForOneUserView(
            new GetReadingStatusForOneUserRequest(
                UserIdentifier::fromIdentifier($this->identifierFactory->create()),
                ArrayList::create([]),
                ArrayList::create([]),
            )
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReadingStatusForOneUserTestSuccessResponseWithRoleId(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => [
                                    'response_from_api',
                                ],
                                'errors' => null,
                            ],
                        )
                    ),
                ),
            ],
        );

        $response = $this->query->getReadingStatusForOneUserView(
            new GetReadingStatusForOneUserRequest(
                UserIdentifier::fromIdentifier($this->identifierFactory->create()),
                ArrayList::create([RoleIdentifier::fromIdentifier($this->identifierFactory->create())]),
                ArrayList::create([]),
            )
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReadingStatusForOneUserTestSuccessResponseWithDocumentId(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => [
                                    'response_from_api',
                                ],
                                'errors' => null,
                            ],
                        )
                    )
                ),
            ],
        );

        $response = $this->query->getReadingStatusForOneUserView(
            new GetReadingStatusForOneUserRequest(
                UserIdentifier::fromIdentifier($this->identifierFactory->create()),
                ArrayList::create([]),
                ArrayList::create([DocumentIdentifier::fromIdentifier($this->identifierFactory->create())]),
            )
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReadingStatusForOneUserTestWillReturnInternalServerErrorResponse(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => null,
                                'errors' => [
                                    'exception' => 'Internal server error.',
                                ],
                            ],
                        )
                    ),
                    [
                        'http_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    ],
                ),
            ],
        );

        $response = $this->query->getReadingStatusForOneUserView(
            new GetReadingStatusForOneUserRequest(
                UserIdentifier::fromIdentifier($this->identifierFactory->create()),
                ArrayList::create([RoleIdentifier::fromIdentifier($this->identifierFactory->create())]),
                ArrayList::create([DocumentIdentifier::fromIdentifier($this->identifierFactory->create())]),
            )
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this::assertInstanceOf(InternalServerErrorView::class, $response);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->httpClient = $container->get(HttpClientInterface::class);
        $this->query = $container->get(GetReadingStatusForOneUserQueryInterface::class);
        $this->jsonEncoder = $container->get(JsonEncoderInterface::class);
        $this->identifierFactory = $container->get(IdentifierFactoryInterface::class);
    }
}
