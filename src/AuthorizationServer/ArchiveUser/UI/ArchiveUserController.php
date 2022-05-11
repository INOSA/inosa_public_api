<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\UI;

use App\AuthorizationServer\ArchiveUser\Application\ArchiveUserRequest;
use App\AuthorizationServer\ArchiveUser\Application\Command\ArchiveUserCommandFactory;
use App\Shared\Application\CommandHandlerException;
use App\Shared\Application\Json\JsonDecoderInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Infrastructure\Controller\ClientCredentialsAuthorizationController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class ArchiveUserController extends ClientCredentialsAuthorizationController
{
    public function archiveUser(
        Request $request,
        DenormalizerInterface $serializer,
        JsonDecoderInterface $jsonDecoder,
        MessageBusInterface $messageBus,
        ArchiveUserCommandFactory $archiveUserCommandFactory,
    ): JsonResponse {
        /** @var ArchiveUserRequest $archiveUserRequest */
        $archiveUserRequest = $serializer->denormalize(
            [
                'userId' => $request->get('userId'),
                'content' => $jsonDecoder->decode($request->getContent())->toArray(),
            ],
            ArchiveUserRequest::class,
        );

        try {
            $messageBus->dispatch($archiveUserCommandFactory->create($archiveUserRequest));

            return new JsonResponse(null, 204);
        } catch (CommandHandlerException $e) {
            return new JsonResponse(['data' => [], 'error' => $e->getMessage()], $e->getCode());
        }
    }
}
