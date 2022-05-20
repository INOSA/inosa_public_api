<?php

declare(strict_types=1);

namespace App\AuthorizationServer\DeleteUser\Infrastructure;

use App\AuthorizationServer\DeleteUser\Application\DeleteUserRequest;
use LogicException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class DeleteUserNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === DeleteUserRequest::class;
    }

    /**
     * @inheritDoc
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): DeleteUserRequest
    {
        if (false === is_array($data)) {
            throw new LogicException('Data has to be array');
        }

        $constraint = new Assert\Collection(
            [
                'fields' => [
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
                    'itemsResponsibleId' => new Assert\Optional(
                        [
                            new Assert\NotBlank(['message' => 'itemsResponsibleId field must not be blank']),
                            new Assert\Type(
                                [
                                    'type' => 'string',
                                    'message' => 'itemsResponsibleId field must be of type string',
                                ],
                            ),
                            new Assert\Uuid(['message' => 'itemsResponsibleId field must be a valid uuid']),
                        ],
                    ),
                    'coSignerResponsibleId' => new Assert\Optional(
                        [
                            new Assert\NotBlank(['message' => 'coSignerResponsibleId field must not be blank']),
                            new Assert\Type(
                                [
                                    'type' => 'string',
                                    'message' => 'coSignerResponsibleId field must be of type string',
                                ],
                            ),
                            new Assert\Uuid(['message' => 'coSignerResponsibleId field must be a valid uuid']),
                        ],
                    ),
                ],
                'allowExtraFields' => true,
            ]
        );
        $violations = $this->validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            throw new BadRequestHttpException((string) $violations->get(0)->getMessage());
        }

        return new DeleteUserRequest(
            $data['userId'],
            $data['itemsResponsibleId'] ?? null,
            $data['coSignerResponsibleId'] ?? null,
        );
    }
}
