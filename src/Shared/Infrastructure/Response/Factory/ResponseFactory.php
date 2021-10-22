<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Response\Factory;

use App\Shared\Application\Query\ResponseViewInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ResponseFactory
{
    public function fromView(ResponseViewInterface $responseView): JsonResponse
    {
        return new JsonResponse(
            data: $responseView->getResponse(),
            status: $responseView->getStatusCode(),
            json: true
        );
    }
}
