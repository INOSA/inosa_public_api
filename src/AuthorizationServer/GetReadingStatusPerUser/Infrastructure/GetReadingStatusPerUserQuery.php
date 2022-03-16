<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerUser\Infrastructure;

use App\AuthorizationServer\GetReadingStatusPerUser\Application\GetReadingStatusPerUserQueryInterface;
use App\AuthorizationServer\GetReadingStatusPerUser\Application\GetReadingStatusPerUserRequest;
use App\AuthorizationServer\GetReadingStatusPerUser\Domain\GetReadingStatusPerUserApi;
use App\AuthorizationServer\GetReadingStatusPerUser\Domain\GetReadingStatusPerUserEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;

final class GetReadingStatusPerUserQuery implements GetReadingStatusPerUserQueryInterface
{
    public function __construct(private GetReadingStatusPerUserApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getReadingStatusPerUser(GetReadingStatusPerUserRequest $request): ResponseViewInterface
    {
        $response = $this->api->request(new GetReadingStatusPerUserEndpoint($request->departmentIds));

        return $this->viewFactory->fromProxyResponse($response);
    }
}
