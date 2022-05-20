<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Application\Command;

use App\AuthorizationServer\DeleteUser\Application\DeleteUserRequest;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\UserIdentifier;

final class DeleteUserCommandFactory
{
    public function __construct(private readonly IdentifierFactoryInterface $uuidFactory)
    {
    }

    public function create(DeleteUserRequest $request): DeleteUserCommand
    {
        return new DeleteUserCommand(
            UserIdentifier::fromIdentifier($this->uuidFactory->fromString($request->userId)),
            $this->getItemsResponsibleId($request),
            $this->getCoSignerResponsibleId($request),
        );
    }

    private function getItemsResponsibleId(DeleteUserRequest $request): ?UserIdentifier
    {
        if (null === $request->itemsResponsibleId) {
            return null;
        }

        return UserIdentifier::fromIdentifier($this->uuidFactory->fromString($request->itemsResponsibleId));
    }

    private function getCoSignerResponsibleId(DeleteUserRequest $request): ?UserIdentifier
    {
        if (null === $request->coSignerResponsibleId) {
            return null;
        }

        return UserIdentifier::fromIdentifier($this->uuidFactory->fromString($request->coSignerResponsibleId));
    }
}
