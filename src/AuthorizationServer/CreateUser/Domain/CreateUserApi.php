<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\ProxyResponse;

final class CreateUserApi
{
    public function __construct(private CreateUserHttpClientInterface $httpClient)
    {
    }

    /**
     * @throws CreateUserException
     */
    public function request(PostEndpointInterface $endpoint): ProxyResponse
    {
        $response = $this->httpClient->request($endpoint);

        if (false === $response->isSuccess()) {
            throw new CreateUserException(
                $response->getResponseContent()->toString(),
                $response->getResponseCode()->asInt()
            );
        }

        return $response;
    }
}
