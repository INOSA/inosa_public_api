<?php

declare(strict_types=1);

namespace App\Shared\Application\Query\Factory;

use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Application\Query\ResponseViewInterface;
use App\Shared\Application\Query\View;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Domain\ResponseCode;
use Inosa\Arrays\ArrayHashMap;

final class ViewFactory
{
    public function __construct(private JsonEncoderInterface $jsonEncoder)
    {
    }

    public function fromProxyResponse(ProxyResponse $proxyResponse): ResponseViewInterface
    {
        if ($proxyResponse->getResponseCode()->equals(ResponseCode::internalServerError())) {
            return $this->internalServerError();
        }

        return $this->view($proxyResponse);
    }

    private function view(ProxyResponse $response): ResponseViewInterface
    {
        return new View(
            $response->getResponseContent()->asString(),
            $response->getResponseCode()->asInt(),
        );
    }

    private function internalServerError(): InternalServerErrorView
    {
        return new InternalServerErrorView(
            $this->jsonEncoder->encode(
                ArrayHashMap::create(
                    [
                        'data' => null,
                        'error' => 'Internal Server Error, please contact with the Administrator.',
                    ]
                )
            ),
            500
        );
    }
}
