<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\Url\Url;

final class GetDepartmentsEndpoint implements EndpointInterface
{
    private const URL = 'departments';

    public function __construct(private InosaSiteIdentifier $siteIdentifier)
    {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf('%s?siteId=%s', self::URL, $this->siteIdentifier->toString()));
    }
}
