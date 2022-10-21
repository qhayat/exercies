<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function save(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array|String[] $filters
     * @return array|Author[]
     */
    public function search(array $filters = []): array
    {
        $query = $this->createQueryBuilder('a');

        if (isset($filters['lastName'])) {
            $query->andWhere('a.lastName = :lastName')->setParameter('lastName', $filters['lastName']);
        }

        if (isset($filters['firstName'])) {
            $query->andWhere('a.firstName = :firstName')->setParameter('firstName', $filters['firstName']);
        }

        return $query->getQuery()->getResult();
    }
}