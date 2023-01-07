<?php

namespace App\Repository;

use App\Entity\Ventes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ventes>
 *
 * @method Ventes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ventes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ventes[]    findAll()
 * @method Ventes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VentesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ventes::class);
    }

    public function save(Ventes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ventes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Ventes[] Returns an array of Ventes objects
     */
    public function findForPager(): QueryBuilder
    {
        return $this->createQueryBuilder('v')
        ;
    }

//    public function findOneBySomeField($value): ?Ventes
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
