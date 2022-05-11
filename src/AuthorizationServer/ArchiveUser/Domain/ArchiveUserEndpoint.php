<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;

final class ArchiveUserEndpoint implements PutEndpointInterface
{
    public const URL = 'users/%s/archive';

    public function __construct(
        private readonly UserIdentifier $userIdentifier,
        private readonly KeepRole $keepRole,
        private readonly ?UserIdentifier $deviationsResponsibleUserId,
        private readonly ?UserIdentifier $actionsResponsibleUserId,
        private readonly ?UserIdentifier $itemsResponsibleId,
        private readonly ?UserIdentifier $coSignerResponsibleId,
    ) {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf(self::URL, $this->userIdentifier->toString()));
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ArrayHashMap
    {
        $params = [
            'keepRoles' => $this->keepRole->shouldKeepRole,
        ];

        if (null !== $this->deviationsResponsibleUserId) {
            $params['newDeviationsResponsibleId'] = $this->deviationsResponsibleUserId->toString();
        }

        if (null !== $this->actionsResponsibleUserId) {
            $params['newActionsResponsibleId'] = $this->actionsResponsibleUserId->toString();
        }

        if (null !== $this->itemsResponsibleId) {
            $params['itemsNewOwnerId'] = $this->itemsResponsibleId->toString();
        }

        if (null !== $this->coSignerResponsibleId) {
            $params['itemsNewOwnerId'] = $this->coSignerResponsibleId->toString();
        }

        return ArrayHashMap::create($params);
    }
}
