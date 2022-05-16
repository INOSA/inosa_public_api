<?php

declare(strict_types=1);

namespace App\Tests\AuthorizationServer\ArchiveUser\Application;

use App\AuthorizationServer\ArchiveUser\Application\ArchiveUserRequest;
use App\AuthorizationServer\ArchiveUser\Application\Command\ArchiveUserCommandFactory;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Infrastructure\Identifier\UuidIdentifierFactory;
use App\Tests\UnitTestCase;

final class ArchiveUserCommandFactoryTest extends UnitTestCase
{
    private ArchiveUserCommandFactory $archiveUserCommandFactory;

    public function testCreateArchiveUserCommandWithNoRequestData(): void
    {
        $request = new ArchiveUserRequest('545e6429-9453-4875-afca-f2048f99174d');

        $command = $this->archiveUserCommandFactory->create($request);

        self::assertEquals(
            new UserIdentifier('545e6429-9453-4875-afca-f2048f99174d'),
            $command->userIdentifier,
        );
        self::assertNull($command->deviationsResponsibleUserId);
        self::assertNull($command->actionsResponsibleUserId);
        self::assertNull($command->itemsResponsibleId);
        self::assertNull($command->coSignerResponsibleId);
        self::assertTrue($command->keepRole->shouldKeepRole);
    }

    public function testCreateArchiveUserCommandWithRequestData(): void
    {
        $request = new ArchiveUserRequest(
            '545e6429-9453-4875-afca-f2048f99174d',
            false,
            '2d9df0eb-6cb2-4c22-bf38-05b19e74cb3c',
            '865001dd-af9a-414d-a7d1-033c1dabfe48',
            'c759b2b0-af69-48e5-a45e-0a15023690c5',
            'ca21b276-98a5-49aa-8535-f0df6cfdfd65',
        );

        $command = $this->archiveUserCommandFactory->create($request);

        self::assertEquals(
            new UserIdentifier('545e6429-9453-4875-afca-f2048f99174d'),
            $command->userIdentifier,
        );
        self::assertFalse($command->keepRole->shouldKeepRole);
        self::assertEquals(
            new UserIdentifier('2d9df0eb-6cb2-4c22-bf38-05b19e74cb3c'),
            $command->deviationsResponsibleUserId,
        );
        self::assertEquals(
            new UserIdentifier('865001dd-af9a-414d-a7d1-033c1dabfe48'),
            $command->actionsResponsibleUserId,
        );
        self::assertEquals(
            new UserIdentifier('c759b2b0-af69-48e5-a45e-0a15023690c5'),
            $command->itemsResponsibleId,
        );
        self::assertEquals(
            new UserIdentifier('ca21b276-98a5-49aa-8535-f0df6cfdfd65'),
            $command->coSignerResponsibleId,
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->archiveUserCommandFactory = new ArchiveUserCommandFactory(new UuidIdentifierFactory());
    }
}
