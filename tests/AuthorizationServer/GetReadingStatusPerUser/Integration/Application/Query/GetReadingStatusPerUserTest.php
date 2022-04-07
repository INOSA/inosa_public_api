<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetReadingStatusPerUser\Integration\Application\Query;

use App\AuthorizationServer\GetReadingStatusPerUser\Application\GetReadingStatusPerUserQueryInterface;
use App\AuthorizationServer\GetReadingStatusPerUser\Application\GetReadingStatusPerUserRequest;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetReadingStatusPerUserTest extends IntegrationTestCase
{
    private GetReadingStatusPerUserQueryInterface $query;
    private MockHttpClient $httpClient;
    private JsonEncoderInterface $jsonEncoder;

    public function testGetReadingStatusPerUserTestSuccessResponse(): void
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

        $response = $this->query->getReadingStatusPerUser(new GetReadingStatusPerUserRequest(ArrayList::create([])));

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReadingStatusPerUserTestWillReturnInternalServerErrorResponse(): void
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

        $response = $this->query->getReadingStatusPerUser(new GetReadingStatusPerUserRequest(ArrayList::create([])));

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this::assertInstanceOf(InternalServerErrorView::class, $response);
    }

    public function testGetReadingStatusPerDepartmentTestWillReturnNotFoundResponse(): void
    {
        $this->httpClient->setResponseFactory(
            [
                new MockResponse(
                    $this->jsonEncoder->encode(
                        ArrayHashMap::create(
                            [
                                'data' => null,
                                'errors' => [
                                    'exception' => 'Exception Message',
                                ],
                            ],
                        )
                    ),
                    [
                        'http_code' => Response::HTTP_NOT_FOUND,
                    ],
                ),
            ],
        );

        $response = $this->query->getReadingStatusPerUser(new GetReadingStatusPerUserRequest(ArrayList::create([])));

        $this::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetReadingStatusPerDepartmentRequestWithDepartmentParameter(): void
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

        $response = $this->query->getReadingStatusPerUser(
            new GetReadingStatusPerUserRequest(
                ArrayList::create(
                    [
                        new DepartmentIdentifier('79fdac72-815e-4345-8a37-975eaa1114f8'),
                    ],
                )
            )
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->httpClient = $container->get(HttpClientInterface::class);
        $this->query = $container->get(GetReadingStatusPerUserQueryInterface::class);
        $this->jsonEncoder = $container->get(JsonEncoderInterface::class);
    }
}
