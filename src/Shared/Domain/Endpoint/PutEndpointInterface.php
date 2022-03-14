<?php

declare(strict_types=1);

namespace App\Shared\Domain\Endpoint;

use Inosa\Arrays\ArrayHashMap;

interface PutEndpointInterface extends EndpointInterface
{
    /**
     * @return ArrayHashMap<mixed>
     */
    public function getParams(): ArrayHashMap;
}
