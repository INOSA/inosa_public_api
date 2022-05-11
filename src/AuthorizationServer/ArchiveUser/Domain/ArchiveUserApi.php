<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class ArchiveUserApi
{
    public function __construct(private readonly ArchiveUserHttpClientInterface $httpClient)
    {
    }

    /**
     * @throws ArchiveUserException
     */
    public function request(PutEndpointInterface $patchEndpoint): ProxyResponse
    {
        $response = $this->httpClient->request($patchEndpoint);

        if (false === $response->isSuccess()) {
            throw new ArchiveUserException(
                $response->getResponseContent()->toString(),
                $response->getResponseCode()->asInt()
            );
        }

        return $response;
    }
}
