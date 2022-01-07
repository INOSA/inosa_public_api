<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\Infrastructure;

use App\AuthorizationServer\GetUsers\Application\GetUsersQueryInterface;
use App\AuthorizationServer\GetUsers\Application\GetUsersRequest;
use App\AuthorizationServer\GetUsers\Domain\GetUsersApi;
use App\AuthorizationServer\GetUsers\Domain\GetUsersEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;

final class GetUsersQuery implements GetUsersQueryInterface
{
    public function __construct(private GetUsersApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getUsers(GetUsersRequest $request): ResponseViewInterface
    {
        $proxyResponse = $this->api->request(
            new GetUsersEndpoint(
                $request->departmentIds,
                $request->firstName,
                $request->lastName,
                $request->userName,
                $request->email,
            )
        );

        return $this->viewFactory->fromProxyResponse($proxyResponse);
    }
}
