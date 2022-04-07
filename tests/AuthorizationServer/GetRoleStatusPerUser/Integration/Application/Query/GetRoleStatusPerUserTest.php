<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetRoleStatusPerUser\Integration\Application\Query;

use App\AuthorizationServer\GetRoleStatusPerUser\Application\Query\GetRoleStatusPerUserQueryInterface;
use App\AuthorizationServer\GetRoleStatusPerUser\Application\Query\GetRoleStatusPerUserRequest;
use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Tests\IntegrationTestCase;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetRoleStatusPerUserTest extends IntegrationTestCase
{
    private GetRoleStatusPerUserQueryInterface $query;
    private MockHttpClient $httpClient;
    private JsonEncoderInterface $jsonEncoder;

    public function testGetReadingStatusPerDepartmentTestSuccessResponse(): void
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

        $response = $this->query->getRoleStatusPerUserView(
            new GetRoleStatusPerUserRequest(ArrayList::create([]))
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReadingStatusPerDepartmentTestWillReturnInternalServerErrorResponse(): void
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

        $response = $this->query->getRoleStatusPerUserView(
            new GetRoleStatusPerUserRequest(ArrayList::create([]))
        );

        $this::assertJson($response->getResponseContent());
        $this::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this::assertInstanceOf(InternalServerErrorView::class, $response);
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
                    )
                ),
            ],
        );

        $response = $this->query->getRoleStatusPerUserView(
            new GetRoleStatusPerUserRequest(
                ArrayList::create(
                    [
                        new RoleIdentifier('9c32f47b-dfa6-4577-98e5-21790d82b815'),
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
        $this->query = $container->get(GetRoleStatusPerUserQueryInterface::class);
        $this->jsonEncoder = $container->get(JsonEncoderInterface::class);
    }
}
