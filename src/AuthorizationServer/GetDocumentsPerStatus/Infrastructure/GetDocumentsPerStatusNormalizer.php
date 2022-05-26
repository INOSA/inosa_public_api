<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetDocumentsPerStatus\Infrastructure;

use App\AuthorizationServer\GetDocumentsPerStatus\Application\GetDocumentsPerStatusRequest;
use App\Shared\Domain\DocumentType;
use LogicException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class GetDocumentsPerStatusNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === GetDocumentsPerStatusRequest::class;
    }

    /**
     * @inheritDoc
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): GetDocumentsPerStatusRequest
    {
        if (false === is_array($data)) {
            throw new LogicException('Data has to be array');
        }

        $constraint = new Assert\Collection(
            [
                'fields' => [
                    'documentType' => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'array',
                                    'message' => 'documentType field must be of type array',
                                ],
                            ),
                            new Assert\All(
                                [
                                    new Assert\Choice(
                                        [
                                            'callback' => [DocumentType::class, 'getDocumentTypes'],
                                            'message' => '{{ value }} is not valid type. Available types: {{ choices }}',
                                        ]
                                    ),
                                ],
                            ),
                        ],
                    ),
                    'authorId' => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'array',
                                    'message' => 'authorId field must be of type array',
                                ],
                            ),
                            new Assert\All(
                                [
                                    new Assert\Uuid(['message' => 'authorId must be a valid uuid']),
                                ],
                            ),
                        ],
                    ),
                    'verifierId' => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'array',
                                    'message' => 'verifierId field must be of type array',
                                ],
                            ),
                            new Assert\All(
                                [
                                    new Assert\Uuid(['message' => 'verifierId must be a valid uuid']),
                                ],
                            ),
                        ],
                    ),
                    'approverId' => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'array',
                                    'message' => 'approverId field must be of type array',
                                ],
                            ),
                            new Assert\All(
                                [
                                    new Assert\Uuid(['message' => 'approverId must be a valid uuid']),
                                ],
                            ),
                        ],
                    ),
                    'folderId' => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'array',
                                    'message' => 'folderId field must be of type array',
                                ],
                            ),
                            new Assert\All(
                                [
                                    new Assert\Uuid(['message' => 'folderId must be a valid uuid']),
                                ],
                            ),
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

        return new GetDocumentsPerStatusRequest(
            $data['documentType'] ?? [],
            $data['authorId'] ?? [],
            $data['verifierId'] ?? [],
            $data['approverId'] ?? [],
            $data['folderId'] ?? [],
        );
    }
}
