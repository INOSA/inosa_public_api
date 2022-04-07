<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\Infrastructure;

use App\AuthorizationServer\GetDepartments\Application\GetDepartmentsQueryInterface;
use App\AuthorizationServer\GetDepartments\Domain\GetDepartmentsApi;
use App\AuthorizationServer\GetDepartments\Domain\GetDepartmentsEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class GetDepartmentsQuery implements GetDepartmentsQueryInterface
{
    public function __construct(private GetDepartmentsApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getDepartments(InosaSiteIdentifier $siteIdentifier): ResponseViewInterface
    {
        $proxyResponse = $this->api->request(new GetDepartmentsEndpoint($siteIdentifier));

        return $this->viewFactory->fromProxyResponse($proxyResponse);
    }
}
