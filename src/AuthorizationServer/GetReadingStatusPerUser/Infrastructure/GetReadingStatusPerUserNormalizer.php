<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerUser\Infrastructure;

use App\AuthorizationServer\GetReadingStatusPerUser\Application\GetReadingStatusPerUserRequest;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Infrastructure\Identifier\InvalidIdentifierException;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class GetReadingStatusPerUserNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private IdentifierFactoryInterface $identifierFactory)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return GetReadingStatusPerUserRequest::class === $type;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        if (!is_array($data)) {
            throw new BadRequestHttpException(
                'departmentId must be an array parameter e.g. departmentId[]=<valid_uuid>'
            );
        }

        try {
            $departmentIds = ArrayList::create($data)
                ->transform(
                    fn(string $id): DepartmentIdentifier => DepartmentIdentifier::fromIdentifier(
                        $this->identifierFactory->fromString($id)
                    )
                )->unique();

            return new GetReadingStatusPerUserRequest($departmentIds);
        } catch (InvalidIdentifierException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }
}
