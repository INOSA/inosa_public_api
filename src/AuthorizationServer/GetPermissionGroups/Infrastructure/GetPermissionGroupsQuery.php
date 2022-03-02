<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetPermissionGroups\Infrastructure;

use App\AuthorizationServer\GetPermissionGroups\Application\GetPermissionGroupsQueryInterface;
use App\AuthorizationServer\GetPermissionGroups\Domain\GetPermissionGroupsApi;
use App\AuthorizationServer\GetPermissionGroups\Domain\GetPermissionGroupsEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;

class GetPermissionGroupsQuery implements GetPermissionGroupsQueryInterface
{
    public function __construct(private GetPermissionGroupsApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getGetPermissionGroups(): ResponseViewInterface
    {
        return $this->viewFactory->fromProxyResponse($this->api->request(new GetPermissionGroupsEndpoint()));
    }
}
