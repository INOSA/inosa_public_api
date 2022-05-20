<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Domain;

use App\Shared\Domain\Endpoint\DeleteEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class DeleteUserApi
{
    public function __construct(private readonly DeleteUserHttpClientInterface $deleteUserHttpClient)
    {
    }

    public function request(DeleteEndpointInterface $deleteEndpoint): ProxyResponse
    {
        $response = $this->deleteUserHttpClient->request($deleteEndpoint);

        if (false === $response->isSuccess()) {
            throw new DeleteUserException(
                $response->getResponseContent()->toString(),
                $response->getResponseCode()->asInt()
            );
        }

        return $response;
    }
}
