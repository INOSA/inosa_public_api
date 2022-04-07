<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoles\Infrastructure\Query;

use App\AuthorizationServer\GetRoles\Application\Query\GetRolesQueryInterface;
use App\AuthorizationServer\GetRoles\Domain\GetRolesApi;
use App\AuthorizationServer\GetRoles\Domain\GetRolesEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;

final class GetRolesQuery implements GetRolesQueryInterface
{
    public function __construct(private GetRolesApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getRoles(): ResponseViewInterface
    {
        $proxyResponse = $this->api->request(new GetRolesEndpoint());

        return $this->viewFactory->fromProxyResponse($proxyResponse);
    }
}
