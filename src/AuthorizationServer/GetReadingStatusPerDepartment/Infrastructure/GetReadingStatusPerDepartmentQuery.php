<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\Infrastructure;

use App\AuthorizationServer\GetReadingStatusPerDepartment\Application\Query\GetReadingStatusPerDepartmentQueryInterface;
use App\AuthorizationServer\GetReadingStatusPerDepartment\Domain\GetReadingStatusPerDepartmentApi;
use App\AuthorizationServer\GetReadingStatusPerDepartment\Domain\GetReadingStatusPerDepartmentEndpoint;
use App\Shared\Application\Query\Factory\ViewFactory;
use App\Shared\Application\Query\ResponseViewInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;

final class GetReadingStatusPerDepartmentQuery implements GetReadingStatusPerDepartmentQueryInterface
{
    public function __construct(private GetReadingStatusPerDepartmentApi $api, private ViewFactory $viewFactory)
    {
    }

    public function getReadingStatusPerDepartmentView(InosaSiteIdentifier $inosaSiteIdentifier): ResponseViewInterface
    {
        return $this->viewFactory->fromProxyResponse(
            $this->api->request(new GetReadingStatusPerDepartmentEndpoint($inosaSiteIdentifier))
        );
    }
}
