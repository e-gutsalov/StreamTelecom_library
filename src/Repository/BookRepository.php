<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function add(Book $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Book $entity, bool $flush = true): void
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

    /**
     * @param Book $book
     * @return Book|null
     * @throws NonUniqueResultException
     */
    public function checkBook(Book $book): Book|null
    {
        $qb = $this->createQueryBuilder('b');
        return $qb
            ->select()
            ->orWhere(
                $qb->expr()->andX(
                    $qb->expr()->like('b.name', ':name'),
                    $qb->expr()->like('b.ISBN', ':ISBN')
                ),
                $qb->expr()->andX(
                    $qb->expr()->like('b.name', ':name'),
                    $qb->expr()->like('b.yearPublication', ':yearPublication')
                )
            )
            ->setParameters([
                'name' => $book->getName(),
                'ISBN' => $book->getISBN(),
                'yearPublication' => $book->getYearPublication(),
            ])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @return array|null
     */
    public function allBooks(): ?array
    {
        $qb = $this->createQueryBuilder('b');
        return $qb
            ->select('b', 'a')
            ->join('b.authors', 'a')
            ->getQuery()
            ->getArrayResult()
            ;
    }
}
