<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseFilterByWorkspaceExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    protected $security;
    protected $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    abstract protected function supports(string $resourceClass);

    abstract protected function buildQuery(QueryBuilder $queryBuilder, $workspaceId);

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (
            !$this->supports($resourceClass) ||
            $this->security->isGranted('ROLE_SUPER_ADMIN') ||
            $this->security->isGranted('ROLE_BOT') ||
            null === $user = $this->security->getUser())
        {
            return;
        }
        $token = $this->security->getToken();
        $workspaceId = $token->getAttribute('workspace_id');
        $this->buildQuery($queryBuilder, $workspaceId);
    }
}