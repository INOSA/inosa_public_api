<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\CreateUser\UI;

use App\Tests\AuthorizationServer\CreateUser\CreateUserParamBuilder;
use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

final class CreateUserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CreateUserParamBuilder $createUserParamBuilder;

    public function testCreateUserShouldBeOk(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->build(),
        );

        self::assertResponseIsSuccessful();
    }

    public function testCreateUserReturnBadRequestOnEmptyId(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->emptyId()->build()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestOnIdNotValidUud(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->withId('1234567')->build()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestWithoutIdField(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->buildWithoutId()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestOnInvalidEmail(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->withEmail('thisIsNotValidEmail')->build()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestWithoutEmail(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->buildWithoutEmail()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestOnEmptyFirstName(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->withFirstName("")->build()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestWithoutFirstName(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->buildWithoutFirstName()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestOnEmptyLastName(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->withLastName("")->build()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestWithoutLastName(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->buildWithoutLastName()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestOnEmptyUserName(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->withUserName("")->build()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestWithoutUserName(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->buildWithoutUserName()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserIsSuccessWithRolesAndPermissionGroups(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this
                         ->createUserParamBuilder
                         ->validRequest()
                         ->withRandomRoles()
                         ->withRandomPermissionGroups()
                         ->build()
        );

        self::assertResponseIsSuccessful();
    }

    public function testCreateUserReturnBadRequestWithoutRoles(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->buildWithoutRoles()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateUserReturnBadRequestWithoutPermissionGroups(): void
    {
        $this->client->request(
            method:  'POST',
            uri:     'public-api/api/users',
            server:  $this->getAuthorizationHeader(),
            content: $this->createUserParamBuilder->validRequest()->buildWithoutPermissionGroups()
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->getClient();
        $this->createUserParamBuilder = self::getContainer()->get(CreateUserParamBuilder::class);
    }
}
