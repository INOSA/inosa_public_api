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
                    $this->notBlank('id'),
                    $this->typeString('id'),
                    $this->uuid('id'),
                ],
                'userName' => [
                    $this->notBlank('userName'),
                    $this->typeString('userName'),
                ],
                'firstName' => [
                    $this->notBlank('firstName'),
                    $this->typeString('firstName'),
                ],
                'lastName' => [
                    $this->notBlank('lastName'),
                    $this->typeString('lastName'),
                ],
                'email' => new Assert\Email(),
                'permissionsGroups' => [
                    $this->typeArray('permissionsGroups'),
                    new Assert\All(
                        [
                            $this->uuid('permissionGroups'),
                        ]
                    ),
                ],
                'departmentId' => $this->uuid('departmentId'),
                'roles' => [
                    $this->typeArray('roles'),
                    new Assert\All(
                        [
                            $this->uuid('roles'),
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

    private function notBlank(string $fieldName): Assert\NotBlank
    {
        return new Assert\NotBlank(null, sprintf('%s field is required', $fieldName));
    }

    private function typeString(string $fieldName): Assert\Type
    {
        return new Assert\Type(['type' => 'string'], sprintf('%s field must be of type string', $fieldName));
    }

    private function typeArray(string $fieldName): Assert\Type
    {
        return new Assert\Type(['type' => 'array'], sprintf('%s field must be of type array', $fieldName));
    }

    private function uuid(string $fieldName): Assert\Uuid
    {
        return new Assert\Uuid(null, sprintf('%s field must be a valid UUID', $fieldName));
    }
}
