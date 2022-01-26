<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetRoleStatusPerUser\Infrastructure;

use App\AuthorizationServer\GetRoleStatusPerUser\Application\Query\GetRoleStatusPerUserRequest;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\RoleIdentifier;
use App\Shared\Infrastructure\Identifier\InvalidIdentifierException;
use Inosa\Arrays\ArrayList;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class GetRoleStatusPerUserNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private IdentifierFactoryInterface $identifierFactory)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return GetRoleStatusPerUserRequest::class === $type;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        if (!is_array($data)) {
            throw new BadRequestHttpException(
                'roleId must be an array parameter e.g. roleId[]=<valid_uuid>'
            );
        }

        try {
            $roleIds = ArrayList::create($data)
                ->transform(
                    fn(string $id): RoleIdentifier => RoleIdentifier::fromIdentifier(
                        $this->identifierFactory->fromString($id)
                    )
                )->unique();
        } catch (InvalidIdentifierException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return new GetRoleStatusPerUserRequest($roleIds);
    }
}
