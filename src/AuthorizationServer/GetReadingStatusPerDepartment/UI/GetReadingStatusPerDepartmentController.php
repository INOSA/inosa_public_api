<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\UI;

use App\AuthorizationServer\GetReadingStatusPerDepartment\Application\Query\GetReadingStatusPerDepartmentQueryInterface;
use App\AuthorizationServer\GetReadingStatusPerDepartment\Application\Query\GetReadingStatusPerDepartmentRequest;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use App\Shared\Infrastructure\Response\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class GetReadingStatusPerDepartmentController extends ClientCredentialsAuthorizationController
{
    public function getReadingStatusPerDepartment(
        Request $request,
        GetReadingStatusPerDepartmentQueryInterface $query,
        ResponseFactory $responseFactory,
        DenormalizerInterface $serializer
    ): JsonResponse {
        try {
            $endpointRequest = $serializer->denormalize(
                $request->get('departmentId', []),
                GetReadingStatusPerDepartmentRequest::class,
            );

            return $responseFactory->fromView($query->getReadingStatusPerDepartmentView($endpointRequest));
        } catch (ExceptionInterface $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
