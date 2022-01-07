<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetFoldersBasicStructure\Domain\Endpoint;

use App\Shared\Domain\Endpoint\EndpointInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use App\Shared\Domain\Url\Url;

final class GetFoldersBasicStructureEndpoint implements EndpointInterface
{
    private const URL = 'folders-basic-structure/without-metrics';

    public function __construct(private InosaSiteIdentifier $inosaSiteIdentifier)
    {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf('%s?siteId=%s', self::URL, $this->inosaSiteIdentifier->toString()));
    }
}
