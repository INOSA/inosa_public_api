<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetUsers\Infrastructure;

use App\AuthorizationServer\CreateUser\Domain\User\Email;
use App\AuthorizationServer\CreateUser\Domain\User\FirstName;
use App\AuthorizationServer\CreateUser\Domain\User\LastName;
use App\AuthorizationServer\CreateUser\Domain\User\UserName;
use App\AuthorizationServer\GetUsers\Application\GetUsersRequest;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;
use LogicException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
 */
final class GetUsersNormalizer implements ContextAwareDenormalizerInterface
{
    private const REQUEST_PARAM_FIRSTNAME = 'firstname';
    private const REQUEST_PARAM_LASTNAME = 'lastname';
    private const REQUEST_PARAM_USERNAME = 'username';
    private const REQUEST_PARAM_EMAIL = 'email';
    private const REQUEST_PARAM_DEPARTMENT_ID = 'departmentId';

    public function __construct(
        private ValidatorInterface $validator,
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return GetUsersRequest::class === $type;
    }

    /**
     * @inheritDoc
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): GetUsersRequest
    {
        if (false === is_array($data)) {
            throw new LogicException('Data has to be an array.');
        }

        $assertions = new Assert\Collection(
            [
                'allowExtraFields' => true, //TODO
                'fields' => [
                    self::REQUEST_PARAM_FIRSTNAME => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'string',
                                    'message' => $this->typeStringMessage(self::REQUEST_PARAM_FIRSTNAME),
                                ]
                            ),
                            new Assert\Length(
                                [
                                    'max' => 255,
                                    'maxMessage' => $this->maxLengthMessage(self::REQUEST_PARAM_FIRSTNAME),
                                ]
                            ),
                            new Assert\NotBlank(),
                        ]
                    ),
                    self::REQUEST_PARAM_LASTNAME => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'string',
                                    'message' => $this->typeStringMessage(self::REQUEST_PARAM_LASTNAME),
                                ]
                            ),
                            new Assert\Length(
                                [
                                    'max' => 255,
                                    'maxMessage' => $this->maxLengthMessage(self::REQUEST_PARAM_LASTNAME),
                                ]
                            ),
                            new Assert\NotBlank(),
                        ]
                    ),
                    self::REQUEST_PARAM_USERNAME => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'string',
                                    'message' => $this->typeStringMessage(self::REQUEST_PARAM_USERNAME),
                                ]
                            ),
                            new Assert\Length(
                                [
                                    'max' => 255,
                                    'maxMessage' => $this->maxLengthMessage(self::REQUEST_PARAM_USERNAME),
                                ]
                            ),
                            new Assert\NotBlank(),
                        ]
                    ),
                    self::REQUEST_PARAM_EMAIL => new Assert\Optional(
                        [
                            new Assert\NotBlank(),
                            new Assert\Email(),
                        ],
                    ),
                    self::REQUEST_PARAM_DEPARTMENT_ID => new Assert\Optional(
                        [
                            new Assert\Type(
                                [
                                    'type' => 'array',
                                    'message' => sprintf(
                                        '%s parameter must be of type array',
                                        self::REQUEST_PARAM_DEPARTMENT_ID
                                    ),
                                ],
                            ),
                            new Assert\All(
                                [
                                    new Assert\Uuid(
                                        [
                                            'message' => sprintf(
                                                '%s parameter must be a valid UUID',
                                                self::REQUEST_PARAM_DEPARTMENT_ID
                                            ),
                                        ],
                                    ),
                                ],
                            ),
                        ],
                    ),
                ],
            ],
        );

        $violations = $this->validator->validate($data, $assertions);

        if ($violations->count() > 0) {
            throw new BadRequestHttpException((string) $violations->get(0)->getMessage());
        }

        $requestData = ArrayHashMap::create($data);

        return new GetUsersRequest(
            $this->getDepartmentIds($requestData),
            $this->getFirstname($requestData),
            $this->getLastName($requestData),
            $this->getUsername($requestData),
            $this->getEmail($requestData),
        );
    }

    /**
     * @param ArrayHashMap<string> $requestData
     */
    private function getFirstname(ArrayHashMap $requestData): ?FirstName
    {
        if (false === $requestData->has(self::REQUEST_PARAM_FIRSTNAME)) {
            return null;
        }

        return new FirstName($requestData->get(self::REQUEST_PARAM_FIRSTNAME));
    }

    /**
     * @param ArrayHashMap<string> $requestData
     */
    private function getLastName(ArrayHashMap $requestData): ?LastName
    {
        if (false === $requestData->has(self::REQUEST_PARAM_LASTNAME)) {
            return null;
        }

        return new LastName($requestData->get(self::REQUEST_PARAM_LASTNAME));
    }

    /**
     * @param ArrayHashMap<string> $requestData
     */
    private function getUsername(ArrayHashMap $requestData): ?UserName
    {
        if (false === $requestData->has(self::REQUEST_PARAM_USERNAME)) {
            return null;
        }

        return new UserName($requestData->get(self::REQUEST_PARAM_USERNAME));
    }

    /**
     * @param ArrayHashMap<string> $requestData
     */
    private function getEmail(ArrayHashMap $requestData): ?Email
    {
        if (false === $requestData->has(self::REQUEST_PARAM_EMAIL)) {
            return null;
        }

        return new Email($requestData->get(self::REQUEST_PARAM_EMAIL));
    }

    /**
     * @param ArrayHashMap<string> $requestData
     * @return ArrayList<DepartmentIdentifier>
     */
    private function getDepartmentIds(ArrayHashMap $requestData): ArrayList
    {
        if (false === $requestData->has(self::REQUEST_PARAM_DEPARTMENT_ID)) {
            return ArrayList::create([]);
        }

        /** @var string[] */
        $departmentIds = $requestData->get(self::REQUEST_PARAM_DEPARTMENT_ID);

        return ArrayList::create($departmentIds)
            ->transform(
                fn(string $departmentId): DepartmentIdentifier => DepartmentIdentifier::fromIdentifier(
                    $this->identifierFactory->fromString($departmentId)
                )
            );
    }

    private function typeStringMessage(string $parameterName): string
    {
        return sprintf('%s parameter must be of type string', $parameterName);
    }

    private function maxLengthMessage(string $parameterName): string
    {
        return sprintf('%s must not be longer than %d characters', $parameterName, 255);
    }
}
