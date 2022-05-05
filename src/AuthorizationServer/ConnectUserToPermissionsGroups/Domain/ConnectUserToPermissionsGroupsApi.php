<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class ConnectUserToPermissionsGroupsApi
{
    public function __construct(private ConnectUserToPermissionsGroupsHttpClientInterface $httpClient)
    {
    }

    /**
     * @throws ConnectUserToPermissionsGroupsException
     */
    public function request(PutEndpointInterface $putEndpoint): ProxyResponse
    {
        $response = $this->httpClient->request($putEndpoint);

        if (false === $response->isSuccess()) {
            throw new ConnectUserToPermissionsGroupsException(
                $response->getResponseContent()->toString(),
                $response->getResponseCode()->asInt()
            );
        }

        return $response;
    }
}
