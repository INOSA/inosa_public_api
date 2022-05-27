<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetReadingStatusPerUser\Smoke\UI;

use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetReadingStatusPerUserTest extends WebTestCase
{
    public function testGetReadingStatusPerUserSmokeTestReturnSuccess(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri:    'public-api/api/users/reading-status',
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider departmentIdParameterDataProvider
     */
    public function testGetReadingStatusPerDepartmentDepartmentIdParamTest(
        string $departmentIdParam,
        int $responseStatusCode,
    ): void {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri:    sprintf('%s?%s', 'public-api/api/users/reading-status', $departmentIdParam),
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseStatusCodeSame($responseStatusCode);
    }

    /**
     * @return array<string, array<int, int|string>>
     */
    public function departmentIdParameterDataProvider(): array
    {
        return [
            'simple string' => ['departmentId[]=abcd', Response::HTTP_BAD_REQUEST],
            'integer' => ['departmentId[]=1', Response::HTTP_BAD_REQUEST],
            'not array parameter' => ['departmentId=f1b3765a-bfab-438b-8e76-79ac28a374f2', Response::HTTP_BAD_REQUEST],
            'array departmentId' => ['departmentId[]=f1b3765a-bfab-438b-8e76-79ac28a374f2', Response::HTTP_OK],
            'two array departmentId' => [
                'departmentId[]=f1b3765a-bfab-438b-8e76-79ac28a374f2&departmentId[]=924d2fba-4bf0-410a-9050-b58ff6c7603f',
                Response::HTTP_OK,
            ],
            'undefined parameter' => ['undefinedParam=5', Response::HTTP_OK],
        ];
    }
}
