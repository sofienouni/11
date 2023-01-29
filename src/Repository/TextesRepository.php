<?php

namespace App\Repository;

use App\Entity\Textes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Textes>
 *
 * @method Textes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Textes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Textes[]    findAll()
 * @method Textes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Textes::class);
    }

    public function save(Textes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Textes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllFieldPaginated(): QueryBuilder
    {
        return $this->createQueryBuilder('t')->orderBy('t.id','desc');
    }

//    /**
//     * @return Textes[] Returns an array of Textes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Textes
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
