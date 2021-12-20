<?php

declare(strict_types=1);

namespace App\Shared\Domain\Endpoint;

use App\Shared\Domain\Url\Url;

interface EndpointInterface
{
    public function getUrl(): Url;
}
