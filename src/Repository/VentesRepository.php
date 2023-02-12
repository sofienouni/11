<?php

namespace App\Repository;

use App\Entity\Ventes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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
            ->orderBy('v.id','desc')
        ;
    }


    /**
     * @return Ventes[] Returns an array of Biens objects
     */
    public function findAllFieldPaginated($values = null): QueryBuilder
    {

        if ($values != null) {
            $nom = $values->getNom();
            $prenom = $values->getPrenom();
            $telephone = $values->getTelephone();
            $typeBien = $values->getTypeBien();
            $villes = $values->getVille();
            if ($villes != 'Choisir une ville' && !empty($villes)){
                foreach ($villes as $ville) {
                    $ville_id[] = $ville->getId();
                }
            }

        }


        $queryBuilder = $this->createQueryBuilder('v')->orderBy('v.id','desc');
        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        if ($values != null) {
            if ($nom != null){
                $criteria->where($expressionBuilder->eq('nom', $nom));
            }

            if ($typeBien != 'Types Du Bien' && !empty($typeBien)) {
                $criteria->Andwhere($expressionBuilder->in('typebien', $typeBien));
            }

            if ($prenom != null && !empty($prenom)) {
                $criteria->Andwhere($expressionBuilder->eq('prenom', $prenom));
            }

            if ($telephone != null && !empty($telephone)) {
                $criteria->Andwhere($expressionBuilder->eq('telephone', $telephone));
            }

            if ($villes != 'Choisir une ville' && !empty($villes)) {
                $criteria->Andwhere($expressionBuilder->in('ville', $ville_id));
            }

        }

        $queryBuilder->addCriteria($criteria);


        return $queryBuilder;

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
