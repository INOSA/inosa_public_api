<?php

declare(strict_types=1);

namespace App\Shared\Application\Query\Factory;

use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\GenericView;
use App\Shared\Application\Query\InternalServerErrorView;
use App\Shared\Application\Query\NotFoundView;
use App\Shared\Domain\ProxyResponse;
use App\Shared\Domain\ResponseContent;
use Inosa\Arrays\ArrayHashMap;

final class ViewFactory
{
    public function __construct(private JsonEncoderInterface $jsonEncoder)
    {
    }

    public function fromProxyResponse(ProxyResponse $proxyResponse): GenericView|InternalServerErrorView|NotFoundView
    {
        return match (true) {
            $proxyResponse->isInternalServerError() => $this->internalServerError(),
            $proxyResponse->isNotFound() => $this->notFound(),
            default => $this->view($proxyResponse)
        };
    }

    private function view(ProxyResponse $response): GenericView
    {
        return new GenericView(
            $response->getResponseContent()->toString(),
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
                        'error' => ResponseContent::internalServerError(),
                    ]
                )
            ),
        );
    }

    private function notFound(): NotFoundView
    {
        return new NotFoundView(
            $this->jsonEncoder->encode(
                ArrayHashMap::create(
                    [
                        'data' => null,
                        'error' => ResponseContent::notFound(),
                    ]
                )
            ),
        );
    }
}
