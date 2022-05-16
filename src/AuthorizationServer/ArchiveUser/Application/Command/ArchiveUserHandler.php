<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Application\Command;

use App\AuthorizationServer\ArchiveUser\Domain\ArchiveUserApi;
use App\AuthorizationServer\ArchiveUser\Domain\ArchiveUserEndpoint;
use App\AuthorizationServer\ArchiveUser\Domain\ArchiveUserException;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\MessageBus\CommandHandlerInterface;

final class ArchiveUserHandler implements CommandHandlerInterface
{
    public function __construct(private readonly ArchiveUserApi $api)
    {
    }

    public function __invoke(ArchiveUserCommand $command): void
    {
        try {
            $this->api->request(
                new ArchiveUserEndpoint(
                    $command->userIdentifier,
                    $command->keepRole,
                    $command->deviationsResponsibleUserId,
                    $command->actionsResponsibleUserId,
                    $command->itemsResponsibleId,
                    $command->coSignerResponsibleId,
                )
            );
        } catch (ArchiveUserException $e) {
            throw new CommandHandlerException(
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }
}
