<?php

declare(strict_types=1);

namespace App\AuthorizationServer\GetReadingStatusPerDepartment\Domain;

use App\Shared\Domain\Endpoint\PostEndpointInterface;
use App\Shared\Domain\Identifier\DepartmentIdentifier;
use App\Shared\Domain\Url\Url;
use Inosa\Arrays\ArrayHashMap;
use Inosa\Arrays\ArrayList;

final class GetReadingStatusPerDepartmentEndpoint implements PostEndpointInterface
{
    private const URL = 'reports/competence/department-overview';

    /**
     * @param ArrayList<DepartmentIdentifier> $departmentIds
     */
    public function __construct(private ArrayList $departmentIds)
    {
    }

    public function getUrl(): Url
    {
        return new Url(sprintf('%s', self::URL));
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ArrayHashMap
    {
        return ArrayHashMap::create(
            [
                'globalFilters' => [
                    'departmentIds' => $this
                        ->departmentIds
                        ->transform(fn(DepartmentIdentifier $identifier): string => $identifier->toString())
                        ->toArray(),
                    'excludeDepartmentIds' => [],
                    'excludeFolderIds' => [],
                    'folderIds' => [],
                ],
                'localFilters' => [],
                'pagination' => [
                    'page' => 0,
                    'size' => -1,
                ],
            ]
        );
    }
}
