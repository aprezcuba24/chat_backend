<?php

namespace App\Repository\Chat;

use App\Entity\Chat\Workspace;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Workspace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workspace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workspace[]    findAll()
 * @method Workspace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkspaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workspace::class);
    }

    public function getByMembers(QueryBuilder $queryBuilder, User $user)
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];
        return $queryBuilder
            ->leftJoin(sprintf('%s.members', $rootAlias), 'member')
            ->andWhere('member.id = :user')
            ->setParameter('user', $user->getId())
        ;
    }
}
