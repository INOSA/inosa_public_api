<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetReadingStatusForOneUser\Smoke\UI;

use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetReadingStatusForOneUserTest extends WebTestCase
{
    private const USER_ID = 'ace3b2a9-c9b5-41ff-9d9b-7b5c2790625e';

    public function testGetReadingStatusForOneUserSmokeTestReturnSuccess(): void
    {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri:    sprintf('api/users/%s/reading-status', self::USER_ID),
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider getReadingStatusForOneUserParamDataProvider
     */
    public function testGetReadingStatusForOneUserParamTest(
        string $userId,
        string $params,
        int $responseStatusCode,
    ): void {
        $client = $this->getClient();
        $client->request(
            method: 'GET',
            uri:    sprintf('api/users/%s/reading-status%s', $userId, $params),
            server: $this->getAuthorizationHeader()
        );

        self::assertResponseStatusCodeSame($responseStatusCode);
    }

    /**
     * @return array<string, array<int, int|string>>
     */
    public function getReadingStatusForOneUserParamDataProvider(): array
    {
        return [
            'empty user id' => ['', '', Response::HTTP_NOT_FOUND],
            'not valid user uuid' => ['invalid_uuid', '', Response::HTTP_BAD_REQUEST],
            'valid user uuid' => [self::USER_ID, '', Response::HTTP_OK],
            'roleId param, not an array, invalid uuid' => [self::USER_ID, '?roleId=invalid_uuid', Response::HTTP_BAD_REQUEST],
            'roleId param, array, invalid uuid' => [self::USER_ID, '?roleId[]=invalid_uuid', Response::HTTP_BAD_REQUEST],
            'roleId param, array, valid uuid' => [self::USER_ID, '?roleId[]=13ea35e9-fa71-4c87-a2a0-7748769d8b22', Response::HTTP_OK],
            'documentId param, not an array, invalid uuid' => [self::USER_ID, '?documentId=invalid_uuid', Response::HTTP_BAD_REQUEST],
            'documentId param, array, invalid uuid' => [self::USER_ID, '?documentId[]=invalid_uuid', Response::HTTP_BAD_REQUEST],
            'documentId param, array, valid uuid' => [self::USER_ID, '?documentId[]=13ea35e9-fa71-4c87-a2a0-7748769d8b22', Response::HTTP_OK],
            'roleId and documentId' => [self::USER_ID, '?roleId[]=ace3b2a9-c9b5-41ff-9d9b-7b5c2790625e&documentId[]=13ea35e9-fa71-4c87-a2a0-7748769d8b22', Response::HTTP_OK],
            'multiple roleId and multiple documentId' => [self::USER_ID, '?roleId[]=ace3b2a9-c9b5-41ff-9d9b-7b5c2790625e&roleId[]=fec299e3-7635-4f14-ab5f-949b654faa43&documentId[]=13ea35e9-fa71-4c87-a2a0-7748769d8b22&documentId[]=c1d57741-7ac0-45fd-a09e-86b61915ac33', Response::HTTP_OK],
        ];
    }
}
