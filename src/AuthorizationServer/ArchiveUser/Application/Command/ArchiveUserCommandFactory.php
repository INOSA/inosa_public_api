<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Application\Command;

use App\AuthorizationServer\ArchiveUser\Application\ArchiveUserRequest;
use App\AuthorizationServer\ArchiveUser\Domain\KeepRole;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\UserIdentifier;

final class ArchiveUserCommandFactory
{
    public function __construct(private readonly IdentifierFactoryInterface $identifierFactory)
    {
    }

    public function create(ArchiveUserRequest $request): ArchiveUserCommand
    {
        return new ArchiveUserCommand(
            UserIdentifier::fromIdentifier($this->identifierFactory->fromString($request->userId)),
            new KeepRole($request->shouldKeepRoles),
            $this->getDeviationsResponsibleId($request),
            $this->getActionsResponsibleId($request),
            $this->getItemsResponsibleId($request),
            $this->getCoSignerResponsibleId($request),
        );
    }

    private function getDeviationsResponsibleId(ArchiveUserRequest $request): ?UserIdentifier
    {
        if (null === $request->deviationsResponsibleId) {
            return null;
        }

        return UserIdentifier::fromIdentifier($this->identifierFactory->fromString($request->deviationsResponsibleId));
    }

    private function getActionsResponsibleId(ArchiveUserRequest $request): ?UserIdentifier
    {
        if (null === $request->actionsResponsibleId) {
            return null;
        }

        return UserIdentifier::fromIdentifier($this->identifierFactory->fromString($request->actionsResponsibleId));
    }

    private function getItemsResponsibleId(ArchiveUserRequest $request): ?UserIdentifier
    {
        if (null === $request->itemsResponsibleId) {
            return null;
        }

        return UserIdentifier::fromIdentifier($this->identifierFactory->fromString($request->itemsResponsibleId));
    }

    private function getCoSignerResponsibleId(ArchiveUserRequest $request): ?UserIdentifier
    {
        if (null === $request->coSignerResponsibleId) {
            return null;
        }

        return UserIdentifier::fromIdentifier($this->identifierFactory->fromString($request->coSignerResponsibleId));
    }
}
