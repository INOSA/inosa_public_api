<?php

declare(strict_types=1);

namespace App\AuthorizationServer\Healthz;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HealthzController extends AbstractController
{
    public function healthz(): JsonResponse
    {
        return new JsonResponse();
    }
}
