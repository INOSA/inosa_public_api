<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDepartments\Domain;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\Url\Url;

final class GetDepartmentsEndpoint implements EndpointInterface
{
    private const URL = 'departments';

    public function __construct()
    {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf('%s?limit=0', self::URL));
    }
}
