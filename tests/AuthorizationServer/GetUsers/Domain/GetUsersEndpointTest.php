<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\GetUsers\Domain;

use App\AuthorizationServer\CreateUser\Domain\User\Email;
use App\AuthorizationServer\CreateUser\Domain\User\FirstName;
use App\AuthorizationServer\CreateUser\Domain\User\LastName;
use App\AuthorizationServer\CreateUser\Domain\User\UserName;
use App\AuthorizationServer\GetUsers\Domain\GetUsersEndpoint;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Tests\UnitTestCase;
use Inosa\Arrays\ArrayList;

final class GetUsersEndpointTest extends UnitTestCase
{
    /**
     * @dataProvider getUsersEndpointDataProvider
     */
    public function testGetUsersEndpointWillReturnCorrectUrl(GetUsersEndpoint $endpoint, string $expectedUrl): void
    {
        self::assertEquals($expectedUrl, $endpoint->getUrl()->toString());
    }

    /**
     * @return array<string, array<int, GetUsersEndpoint|string>>
     */
    public function getUsersEndpointDataProvider(): array
    {
        return [
            'no parameters' => [
                new GetUsersEndpoint(ArrayList::create([])),
                'users',
            ],
            'firstname' => [
                new GetUsersEndpoint(ArrayList::create([]), new FirstName('will')),
                'users?firstname=will',
            ],
            'lastname' => [
                new GetUsersEndpoint(ArrayList::create([]), null, new LastName('smith')),
                'users?lastname=smith',
            ],
            'username' => [
                new GetUsersEndpoint(
                    departmentIds: ArrayList::create([]),
                    userName:      new UserName('w.smith1')
                ),
                'users?username=w.smith1',
            ],
            'email' => [
                new GetUsersEndpoint(
                    departmentIds: ArrayList::create([]),
                    email:         new Email('w.smith1@inosa.no')
                ),
                'users?email=w.smith1@inosa.no',
            ],
            'single departmentId' => [
                new GetUsersEndpoint(
                    ArrayList::create(
                        [
                            new DepartmentIdentifier('e71e57f7-7b8c-4bbf-a134-551a39698541'),
                            new DepartmentIdentifier('690fb6c4-c716-42f8-969c-46e3cb745663'),
                            new DepartmentIdentifier('2b7a50a7-717f-46d3-9b56-73b6efd2d676'),
                        ]
                    )
                ),
                'users?departmentId[]=e71e57f7-7b8c-4bbf-a134-551a39698541&departmentId[]=690fb6c4-c716-42f8-969c-46e3cb745663&departmentId[]=2b7a50a7-717f-46d3-9b56-73b6efd2d676',
            ],
            'firstname and lastname' => [
                new GetUsersEndpoint(
                    ArrayList::create([]),
                    firstName: new FirstName('will'),
                    lastName:  new LastName('smith'),
                ),
                'users?firstname=will&lastname=smith',
            ],
            'lastname and username' => [
                new GetUsersEndpoint(
                    ArrayList::create([]),
                    lastName: new LastName('smith'),
                    userName: new UserName('will123'),
                ),
                'users?lastname=smith&username=will123',
            ],
            'firstname, lastname, username, email' => [
                new GetUsersEndpoint(
                    ArrayList::create([]),
                    firstName: new FirstName('will'),
                    lastName:  new LastName('smith'),
                    userName:  new UserName('will123'),
                    email:     new Email('will123@inosa.no'),
                ),
                'users?firstname=will&lastname=smith&username=will123&email=will123@inosa.no',
            ],
            'firstname, departmentIds' => [
                new GetUsersEndpoint(
                    ArrayList::create(
                        [
                            new DepartmentIdentifier('e71e57f7-7b8c-4bbf-a134-551a39698541'),
                            new DepartmentIdentifier('690fb6c4-c716-42f8-969c-46e3cb745663'),
                            new DepartmentIdentifier('2b7a50a7-717f-46d3-9b56-73b6efd2d676'),
                        ]
                    ),
                    firstName: new FirstName('will')
                ),
                'users?departmentId[]=e71e57f7-7b8c-4bbf-a134-551a39698541&departmentId[]=690fb6c4-c716-42f8-969c-46e3cb745663&departmentId[]=2b7a50a7-717f-46d3-9b56-73b6efd2d676&firstname=will',
            ],
        ];
    }
}
