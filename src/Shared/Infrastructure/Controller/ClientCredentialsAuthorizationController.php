<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller;

use App\Shared\Infrastructure\Client\Entity\ClientEntity;
use App\Shared\Infrastructure\Client\Repository\ClientEntityNotFoundException;
use App\Shared\Infrastructure\Client\Repository\ClientRepository;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientCredentialsAuthorizationController extends AbstractController
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function getClient(): ClientEntity
    {
        try {
            return $this->clientRepository->getClient();
        } catch (ClientEntityNotFoundException) {
            throw new LogicException('Client is not authorized or does not exists');
        }
    }
}