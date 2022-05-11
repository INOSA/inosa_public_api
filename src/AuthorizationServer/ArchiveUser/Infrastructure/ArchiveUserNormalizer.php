<?php

declare(strict_types=1);

namespace App\AuthorizationServer\ArchiveUser\Infrastructure;

use App\AuthorizationServer\ArchiveUser\Application\ArchiveUserRequest;
use LogicException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class ArchiveUserNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === ArchiveUserRequest::class;
    }

    /**
     * @inheritDoc
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): ArchiveUserRequest
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
                            'deviationsResponsibleId' => new Assert\Optional(
                                [
                                    new Assert\Uuid(
                                        [
                                            'message' => 'deviationsResponsibleId field must be a valid uuid',
                                        ],
                                    ),
                                ],
                            ),
                            'actionsResponsibleId' => new Assert\Optional(
                                [
                                    new Assert\Uuid(
                                        [
                                            'message' => 'actionsResponsibleId field must be a valid uuid',
                                        ],
                                    ),
                                ],
                            ),
                            'itemsResponsibleId' => new Assert\Optional(
                                [
                                    new Assert\Uuid(
                                        [
                                            'message' => 'itemsResponsibleId field must be a valid uuid',
                                        ],
                                    ),
                                ],
                            ),
                            'coSignerResponsibleId' => new Assert\Optional(
                                [
                                    new Assert\Uuid(
                                        [
                                            'message' => 'coSignerResponsibleId field must be a valid uuid',
                                        ],
                                    ),
                                ],
                            ),
                            'keepRoles' => new Assert\Optional(
                                [
                                    new Assert\Type(
                                        [
                                            'type' => 'bool',
                                            'message' => 'keepRoles field must be of type bool',
                                        ],
                                    ),
                                ],
                            ),
                        ],
                    ],
                ),
            ]
        );
        $violations = $this->validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            throw new BadRequestHttpException((string) $violations->get(0)->getMessage());
        }
        return new ArchiveUserRequest(
            $data['userId'],
            $data['content']['keepRoles'] ?? null,
            $data['content']['deviationsResponsibleId'] ?? null,
            $data['content']['actionsResponsibleId'] ?? null,
            $data['content']['itemsResponsibleId'] ?? null,
            $data['content']['coSignerResponsibleId'] ?? null,
        );
    }
}
