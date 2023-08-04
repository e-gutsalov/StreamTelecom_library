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

    public function add(DiscardBook $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(DiscardBook $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
