<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToRoles\Domain;

use App\Shared\Domain\Endpoint\PutEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class ConnectUserToRolesApi
{
    public function __construct(private ConnectUserToRolesHttpClientInterface $httpClient)
    {
    }

    /**
     * @throws ConnectUserToRolesException
     */
    public function request(PutEndpointInterface $endpoint): ProxyResponse
    {
        $response = $this->httpClient->request($endpoint);

        if (false === $response->isSuccess()) {
            throw new ConnectUserToRolesException(
                $response->getResponseContent()->toString(),
                $response->getResponseCode()->asInt()
            );
        }

        return $response;
    }
}
