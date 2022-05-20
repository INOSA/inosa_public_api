<?php

declare(strict_types=1);

namespace App\Shared\Domain\Endpoint;

use Inosa\Arrays\ArrayHashMap;

interface DeleteEndpointInterface extends EndpointInterface
{
    /**
     * Unfortunately INOSA supports payload in DELETE endpoint
     * @return ArrayHashMap<mixed>
     */
    public function getParams(): ArrayHashMap;
}
