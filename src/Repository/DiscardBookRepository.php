<?php

namespace App\Repository;

use App\Entity\DiscardBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscardBook>
 *
 * @method DiscardBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscardBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscardBook[]    findAll()
 * @method DiscardBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscardBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscardBook::class);
    }

//    /**
//     * @return DiscardBook[] Returns an array of DiscardBook objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DiscardBook
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
