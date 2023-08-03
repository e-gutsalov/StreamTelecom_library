<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function add(Author $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Author $entity, bool $flush = true): void
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
     * @param Author $author
     * @return Author|null
     * @throws NonUniqueResultException
     */
    public function checkAuthor(Author $author): Author|null
    {
        $qb = $this->createQueryBuilder('a');
        return $qb
            ->select()
            ->andWhere(
                $qb->expr()->like('a.name', ':name'),
                $qb->expr()->like('a.surname', ':surname'),
                $qb->expr()->like('a.patronymic', ':patronymic'),
            )
            ->setParameters([
                'name' => $author->getName(),
                'surname' => $author->getSurname(),
                'patronymic' => $author->getPatronymic(),
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
