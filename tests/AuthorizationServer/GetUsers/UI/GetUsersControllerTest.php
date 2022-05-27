<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetUsers\UI;

use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class GetUsersControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function testShouldBeOk(): void
    {
        $this->client->request(
            method:  'GET',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
        );

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider validParametersDataProvider
     */
    public function testValidParametersThrowBadRequestException(string $queryParams): void
    {
        $this->client->request(
            method:  'GET',
            uri:     sprintf('public-api/api/users?%s', $queryParams),
            server:  $this->getAuthorizationHeader(),
        );

        self::assertResponseStatusCodeSame(200);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function validParametersDataProvider(): array
    {
        return [
            'two departmenIds provided ' => ['departmentId[]=0221bfe7-5278-433d-bddf-1ffd3dedd101&departmentId[]=5103644d-79d7-48a9-abee-f92d2bd3606b'],
        ];
    }

    /**
     * @dataProvider invalidParametersDataProvider
     */
    public function testInvalidParametersThrowBadRequestException(string $queryParams): void
    {
        $this->client->request(
            method:  'GET',
            uri:     sprintf('public-api/api/users?%s', $queryParams),
            server:  $this->getAuthorizationHeader(),
        );

        self::assertResponseStatusCodeSame(400);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidParametersDataProvider(): array
    {
        $tooLongString = random_bytes(256);

        return [
            'too long firstname' => [sprintf('firstname=%s', $tooLongString)],
            'too long lastname' => [sprintf('lastname=%s', $tooLongString)],
            'too long username' => [sprintf('username=%s', $tooLongString)],
            'empty firstname' => ['firstname='],
            'empty lastname' => ['lastname='],
            'empty username' => ['username='],
            'empty email' => ['email='],
            'empty departmentId' => ['departmentId='],
            'invalid email' => ['email=invalid-email'],
            'departmentId not an array param'  => ['departmentId=not-an-array'],
            'departmentId not a valid uuid' => ['departmentId[]=this-is-not-valid-uuid'],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->getClient();
    }
}
