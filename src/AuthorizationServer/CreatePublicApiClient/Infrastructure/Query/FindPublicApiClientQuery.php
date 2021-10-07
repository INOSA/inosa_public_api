<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Query;

use App\AuthorizationServer\CreatePublicApiClient\Application\Query\FindPublicApiClientCreateView;
use App\AuthorizationServer\CreatePublicApiClient\Application\Query\FindPublicApiClientQueryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Inosa\Arrays\ArrayHashMap;
use LogicException;

final class FindPublicApiClientQuery implements FindPublicApiClientQueryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function findByInosaSiteId(InosaSiteIdentifier $id): ?FindPublicApiClientCreateView
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('c.identifier', 'c.secret')
            ->from('client_additional_info', 'cai')
            ->innerJoin('cai', 'oauth2_client', 'c', 'cai.client_id = c.identifier')
            ->andWhere(
                $qb->expr()->eq('cai.inosa_site_id', ':inosa_site_id')
            )
            ->setParameter('inosa_site_id', $id->asString());

        try {
            $result = ArrayHashMap::create($qb->fetchAssociative());

            if ($result->isEmpty()) {
                return null;
            }

            return new FindPublicApiClientCreateView($result->get('identifier'), $result->get('secret'));
        } catch (Exception $exception) {
            throw new LogicException($exception->getMessage());
        }
    }
}
