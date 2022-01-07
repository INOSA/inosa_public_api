<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoleStatusPerUser\Infrastructure;

use App\AuthorizationServer\GetRoleStatusPerUser\Application\Query\GetRoleStatusPerUserQueryInterface;
use App\AuthorizationServer\GetRoleStatusPerUser\Application\Query\GetRoleStatusPerUserRequest;
use App\AuthorizationServer\GetRoleStatusPerUser\Domain\GetRoleStatusPerUserApi;
use App\AuthorizationServer\GetRoleStatusPerUser\Domain\GetRoleStatusPerUserEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;

final class GetRoleStatusPerUserQuery implements GetRoleStatusPerUserQueryInterface
{
    public function __construct(private GetRoleStatusPerUserApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getRoleStatusPerUserView(GetRoleStatusPerUserRequest $request): ResponseViewInterface
    {
        return $this->viewFactory->fromProxyResponse(
            $this->api->request(new GetRoleStatusPerUserEndpoint($request->getRoleIds()))
        );
    }
}
