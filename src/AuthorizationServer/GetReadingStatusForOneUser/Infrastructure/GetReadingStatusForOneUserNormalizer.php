<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusForOneUser\Infrastructure;

use App\AuthorizationServer\GetReadingStatusForOneUser\Application\Query\GetReadingStatusForOneUserRequest;
use App\Shared\Domain\Identifier\DocumentIdentifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Infrastructure\Identifier\InvalidIdentifierException;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class GetReadingStatusForOneUserNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private IdentifierFactoryInterface $identifierFactory)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return GetReadingStatusForOneUserRequest::class === $type;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        $userId = $this->getUserId($data);

        if (null === $userId) {
            throw new BadRequestHttpException('User id must be provided.');
        }

        $roleIds = $this->getArrayRequestParameter('roleIds', $data);
        $documentIds = $this->getArrayRequestParameter('documentIds', $data);

        try {
            $roleUuids = ArrayList::create($roleIds)
                ->transform(
                    fn(string $id): RoleIdentifier => RoleIdentifier::fromIdentifier(
                        $this->identifierFactory->fromString($id)
                    )
                )->unique();

            $documentUuids = ArrayList::create($documentIds)
                ->transform(
                    fn(string $id): DocumentIdentifier => DocumentIdentifier::fromIdentifier(
                        $this->identifierFactory->fromString($id)
                    )
                )->unique();

            return new GetReadingStatusForOneUserRequest(
                UserIdentifier::fromIdentifier($this->identifierFactory->fromString($userId)),
                $roleUuids,
                $documentUuids,
            );
        } catch (InvalidIdentifierException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    private function getUserId(mixed $requestParams): ?string
    {
        if (false === is_array($requestParams)) {
            throw new BadRequestHttpException('Request parameters are incorrect.');
        }

        if (false === array_key_exists('userId', $requestParams)) {
            throw new BadRequestHttpException('userId parameter is mandatory');
        }

        return $requestParams['userId'];
    }

    /**
     * @return array<mixed>
     */
    private function getArrayRequestParameter(string $parameterName, mixed $requestParams): array
    {
        if (false === is_array($requestParams)) {
            throw new BadRequestHttpException('Request parameters are incorrect.');
        }

        if (false === array_key_exists($parameterName, $requestParams)) {
            throw new BadRequestHttpException(sprintf('%s parameter is not provided.', $parameterName));
        }

        $result = $requestParams[$parameterName];

        if (true === is_string($result)) {
            throw new BadRequestHttpException(sprintf('%s parameter must be an array.', $parameterName));
        }

        return $result;
    }
}
