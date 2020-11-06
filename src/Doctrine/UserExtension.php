<?php

namespace App\Doctrine;

use Doctrine\ORM\QueryBuilder;
use App\Entity\User;

class UserExtension extends BaseFilterByWorkspaceExtension
{
    protected function supports(string $resourceClass)
    {
        return User::class === $resourceClass;
    }

    protected function buildQuery(QueryBuilder $queryBuilder, $workspaceId)
    {
        $this->entityManager->getRepository(User::class)->findByWorkspace($queryBuilder, $workspaceId);
    }
}