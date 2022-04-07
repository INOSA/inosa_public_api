<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusForOneUser\Infrastructure;

use App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query\GetReadingStatusForOneUserQueryInterface;
use App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query\GetReadingStatusForOneUserRequest;
use App\AuthorizationServer\GetReadingStatusForOneUser\Domain\GetReadingStatusForOneUserApi;
use App\AuthorizationServer\GetReadingStatusForOneUser\Domain\GetReadingStatusForOneUserEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;

final class GetReadingStatusForOneUserQuery implements GetReadingStatusForOneUserQueryInterface
{
    public function __construct(private GetReadingStatusForOneUserApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getReadingStatusForOneUserView(GetReadingStatusForOneUserRequest $request): ResponseViewInterface
    {
        return $this->viewFactory->fromProxyResponse(
            $this->api->request(
                new GetReadingStatusForOneUserEndpoint(
                    $request->userId,
                    $request->rolesIds,
                    $request->documentsIds
                )
            )
        );
    }
}
