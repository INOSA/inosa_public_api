<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreateUser\Infrastructure;

use App\AuthorizationServer\CreateUser\Application\CreateUserRequest;
use LogicException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class CreateUserRequestNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return CreateUserRequest::class === $type;
    }

    /**
     * @inheritDoc
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): CreateUserRequest
    {
        if (false === is_array($data)) {
            throw new LogicException('Data has to be array');
        }

        $constraint = new Assert\Collection(
            [
                'id' => [
                    new Assert\NotBlank(['message' => 'id field must not be blank']),
                    new Assert\Type(['type' => 'string', 'message' => 'id field must be of type string']),
                    new Assert\Uuid(['message' => 'id field must be a valid uuid']),
                ],
                'userName' => [
                    new Assert\NotBlank(['message' => 'userName field must not be blank']),
                    new Assert\Type(['type' => 'string', 'message' => 'userName field must be of type string']),
                ],
                'firstName' => [
                    new Assert\NotBlank(['message' => 'firstName field must not be blank']),
                    new Assert\Type(['type' => 'string', 'message' => 'firstName field must be of type string']),
                ],
                'lastName' => [
                    new Assert\NotBlank(['message' => 'lastName field must not be blank']),
                    new Assert\Type(['type' => 'string', 'message' => 'lastName field must be of type string']),
                ],
                'email' => new Assert\Email(),
                'permissionsGroups' => [
                    new Assert\Type(['type' => 'array', 'message' => 'permissionsGroups field must be of type array']),
                    new Assert\All(
                        [
                            new Assert\Uuid(['message' => 'permissionsGroups field must be a valid uuid']),
                        ]
                    ),
                ],
                'departmentId' => new Assert\Uuid(['message' => 'departmentId field must be a valid uuid']),
                'roles' => [
                    new Assert\Type(
                        [
                            'type' => 'array',
                            'message' => 'roles field must be of type array',
                        ]
                    ),
                    new Assert\All(
                        [
                            new Assert\Uuid(['message' => 'role id must a valid uuid']),
                        ]
                    ),
                ],
            ]
        );

        $violations = $this->validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            throw new BadRequestHttpException((string) $violations->get(0)->getMessage());
        }

        return new CreateUserRequest(
            $data['id'],
            $data['userName'],
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['permissionsGroups'],
            $data['departmentId'],
            $data['roles'],
        );
    }
}
