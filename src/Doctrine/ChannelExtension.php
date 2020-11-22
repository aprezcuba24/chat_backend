<?php

namespace App\Doctrine;

use Doctrine\ORM\QueryBuilder;
use App\Entity\Chat\Channel;

class ChannelExtension extends BaseFilterByWorkspaceExtension
{
    protected function supports(string $resourceClass)
    {
        return Channel::class === $resourceClass;
    }

    protected function buildQuery(QueryBuilder $queryBuilder, $workspaceId)
    {
        $this->entityManager->getRepository(Channel::class)->findByWorkspace($workspaceId, $queryBuilder);
    }
}