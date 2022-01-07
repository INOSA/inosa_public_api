<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetRoleStatusPerUser\Smoke\UI;

use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetRoleStatusPerUserTest extends WebTestCase
{
    public function testGetRoleStatusPerUserSmokeTestReturnSuccess(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri: 'api/role-status-per-user',
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider roleIdParameterDataProvider
     */
    public function testGetRoleStatusPerUserRoleIdParamTest(
        string $roleIdParam,
        int $responseStatusCode,
    ): void {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri: sprintf('%s?%s', 'api/role-status-per-user', $roleIdParam),
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseStatusCodeSame($responseStatusCode);
    }

    /**
     * @return array<string, array<int, int|string>>
     */
    public function roleIdParameterDataProvider(): array
    {
        return [
            'simple string' => ['roleId[]=abcd', Response::HTTP_BAD_REQUEST],
            'integer' => ['roleId[]=1', Response::HTTP_BAD_REQUEST],
            'not array parameter' => ['roleId=13ea35e9-fa71-4c87-a2a0-7748769d8b22', Response::HTTP_BAD_REQUEST],
            'array departmentId' => ['roleId[]=13ea35e9-fa71-4c87-a2a0-7748769d8b22', Response::HTTP_OK],
            'two array roleId' => [
                'roleId[]=13ea35e9-fa71-4c87-a2a0-7748769d8b22&roleId[]=30e3c1b0-0672-4805-ba55-dc6c290b76c1',
                Response::HTTP_OK,
            ],
            'undefined parameter' => ['undefinedParam=5', Response::HTTP_OK],
        ];
    }
}
