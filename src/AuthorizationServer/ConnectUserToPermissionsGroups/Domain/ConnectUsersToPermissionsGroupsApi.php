<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class ConnectUsersToPermissionsGroupsApi
{
    public function __construct(private ConnectUsersToPermissionsGroupsHttpClientInterface $httpClient)
    {
    }

    /**
     * @throws ConnectUsersToPermissionsGroupsException
     */
    public function request(PutEndpointInterface $putEndpoint): ProxyResponse
    {
        $response = $this->httpClient->request($putEndpoint);

        if (false === $response->isSuccess()) {
            throw new ConnectUsersToPermissionsGroupsException(
                $response->getResponseContent()->toString(),
                $response->getResponseCode()->asInt()
            );
        }

        return $response;
    }
}
