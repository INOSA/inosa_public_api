<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\Url\Url;

final class GetReadingStatusPerDepartmentEndpoint implements EndpointInterface
{
    private const URL = 'reports/competence/department-overview';

    public function __construct(private InosaSiteIdentifier $inosaSiteIdentifier)
    {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf('%s?siteId=%s', self::URL, $this->inosaSiteIdentifier->toString()));
    }
}
