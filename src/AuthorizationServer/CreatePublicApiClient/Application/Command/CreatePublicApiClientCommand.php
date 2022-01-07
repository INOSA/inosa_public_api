<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Application\Command;

use App\AuthorizationServer\CreatePublicApiClient\Domain\Client\ClientName;
use App\Shared\Application\MessageBus\SyncCommandInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class CreatePublicApiClientCommand implements SyncCommandInterface
{
    public function __construct(
        private InosaSiteIdentifier $clientIdentifier,
        private ClientName $clientName,
    ) {
    }

    public function getInosaSiteIdentifier(): InosaSiteIdentifier
    {
        return $this->clientIdentifier;
    }

    public function getClientName(): ClientName
    {
        return $this->clientName;
    }
}
