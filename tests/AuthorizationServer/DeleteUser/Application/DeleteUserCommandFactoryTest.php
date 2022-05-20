<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\DeleteUser\Application;

use App\AuthorizationServer\DeleteUser\Application\Command\DeleteUserCommandFactory;
use App\AuthorizationServer\DeleteUser\Application\DeleteUserRequest;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Infrastructure\Identifier\UuidIdentifierFactory;
use App\Tests\UnitTestCase;

final class DeleteUserCommandFactoryTest extends UnitTestCase
{
    private DeleteUserCommandFactory $deleteUserCommandFactory;

    public function testCreateDeleteUserCommandWithNoRequestData(): void
    {
        $request = new DeleteUserRequest('545e6429-9453-4875-afca-f2048f99174d');

        $command = $this->deleteUserCommandFactory->create($request);

        self::assertEquals(
            new UserIdentifier('545e6429-9453-4875-afca-f2048f99174d'),
            $command->userToDeleteId,
        );
        self::assertNull($command->substituteItemsResponsibleId);
        self::assertNull($command->substituteCoSignerId);
    }

    public function testCreateArchiveUserCommandWithRequestData(): void
    {
        $request = new DeleteUserRequest(
            '1b7669b0-3d5c-43c4-b0fd-27e1eb9aa99e',
            'e6f8bb77-ea9d-4c16-900a-fdbc4b5ba583',
            '865001dd-af9a-414d-a7d1-033c1dabfe48',
        );

        $command = $this->deleteUserCommandFactory->create($request);

        self::assertEquals(
            new UserIdentifier('1b7669b0-3d5c-43c4-b0fd-27e1eb9aa99e'),
            $command->userToDeleteId,
        );
        self::assertEquals(
            new UserIdentifier('e6f8bb77-ea9d-4c16-900a-fdbc4b5ba583'),
            $command->substituteItemsResponsibleId,
        );
        self::assertEquals(
            new UserIdentifier('865001dd-af9a-414d-a7d1-033c1dabfe48'),
            $command->substituteCoSignerId,
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->deleteUserCommandFactory = new DeleteUserCommandFactory(new UuidIdentifierFactory());
    }
}
