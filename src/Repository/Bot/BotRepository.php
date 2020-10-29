<?php

namespace App\Repository\Bot;

use App\Entity\Bot\Bot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bot[]    findAll()
 * @method Bot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bot::class);
    }

    // /**
    //  * @return Bot[] Returns an array of Bot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bot
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
