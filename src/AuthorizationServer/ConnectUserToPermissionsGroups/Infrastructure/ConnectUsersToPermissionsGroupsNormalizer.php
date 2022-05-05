<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ConnectUserToPermissionsGroups\Infrastructure;

use App\AuthorizationServer\ConnectUserToPermissionsGroups\Application\ConnectUsersToPermissionsGroupRequest;
use LogicException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class ConnectUsersToPermissionsGroupsNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return ConnectUsersToPermissionsGroupRequest::class === $type;
    }

    /**
     * @inheritDoc
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        if (false === is_array($data)) {
            throw new LogicException('Data has to be array');
        }

        $constraint = new Assert\Collection(
            [
                'userId' => [
                    new Assert\NotBlank(['message' => 'user id field must not be blank']),
                    new Assert\Type(
                        [
                            'type' => 'string',
                            'message' => 'user id field must be of type string',
                        ],
                    ),
                    new Assert\Uuid(['message' => 'userId field must be a valid uuid']),
                ],
                'content' => new Assert\Collection(
                    [
                        'fields' => [
                            'permissionsGroupsIds' => new Assert\Required(
                                [
                                    new Assert\Type(
                                        [
                                            'type' => 'array',
                                            'message' => 'permissions groups ids field must be of type array',
                                        ],
                                    ),
                                    new Assert\All(
                                        [
                                            new Assert\Uuid(['message' => 'permissions groups id must a valid uuid']),
                                        ],
                                    ),
                                ],
                            ),
                        ],
                        'missingFieldsMessage' => '{{ field }} is missing in payload',
                    ]
                ),
            ]
        );
        $violations = $this->validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            throw new BadRequestHttpException((string) $violations->get(0)->getMessage());
        }

        return new ConnectUsersToPermissionsGroupRequest(
            $data['userId'],
            $data['content']['permissionsGroupsIds'],
        );
    }
}
