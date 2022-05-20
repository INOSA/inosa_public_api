<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Domain;

use App\Shared\Domain\Endpoint\DeleteEndpointInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;

final class DeleteUserEndpoint implements DeleteEndpointInterface
{
    private const URL = 'users/%s';

    public function __construct(
        private readonly UserIdentifier $userToDeleteId,
        private readonly ?UserIdentifier $itemsResponsibleId,
        private readonly ?UserIdentifier $coSignerResponsibleId,
    ) {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf(self::URL, $this->userToDeleteId->toString()));
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ArrayHashMap
    {
        $params = [];

        if (null !== $this->itemsResponsibleId) {
            $params['itemsNewOwnerId'] = $this->itemsResponsibleId->toString();
        }

        if (null !== $this->coSignerResponsibleId) {
            $params['newCoSignerId'] = $this->coSignerResponsibleId->toString();
        }

        return ArrayHashMap::create($params);
    }
}
