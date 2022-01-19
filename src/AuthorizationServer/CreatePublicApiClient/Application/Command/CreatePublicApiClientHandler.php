<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Application\Command;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientCreator;
use App\Shared\Application\MessageBus\CommandHandlerInterface;

final class CreatePublicApiClientHandler implements CommandHandlerInterface
{
    public function __construct(private ClientCreator $clientCreator)
    {
    }

    public function __invoke(CreatePublicApiClientCommand $createPublicApiClient): void
    {
        $this->clientCreator->create(
            $createPublicApiClient->getInosaSiteIdentifier(),
            $createPublicApiClient->getClientName(),
        );
    }
}
