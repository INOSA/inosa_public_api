<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\RateLimiter;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final class PublicApiRateLimiterEventListener
{
    public function __construct(private readonly RateLimiterFactory $authenticatedApiLimiter)
    {
    }

    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        $limiter = $this->authenticatedApiLimiter->create($requestEvent->getRequest()->getClientIp());

        if (false === $limiter->consume()->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
    }
}
