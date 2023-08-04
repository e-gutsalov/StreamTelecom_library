<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return array|null
     */
    public function allBooks(): ?array
    {
        $qb = $this->createQueryBuilder('b');
        return $qb
            ->select('b', 'a')
            ->join('b.authors', 'a')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param array $data
     * @return array|null
     */
    public function searchBookByAuthor(array $data): ?array
    {
        $qb = $this->createQueryBuilder('b');
        $qb
            ->select('b', 'a')
            ->join('b.authors', 'a');

        foreach ($data['authors'] as $key => $author) {
            $qb->orWhere(
                $qb->expr()->andX(
                    $qb->expr()->like('a.name', ':name_'.$key),
                    $qb->expr()->like('a.surname', ':surname_'.$key),
                    $qb->expr()->like('a.patronymic', ':patronymic_'.$key),
                )
            )
                ->setParameter('name_'.$key, $author['name'])
                ->setParameter('surname_'.$key, $author['surname'])
                ->setParameter('patronymic_'.$key, $author['patronymic']);
        }

        return $qb
                ->getQuery()
                ->getArrayResult();
    }
}
