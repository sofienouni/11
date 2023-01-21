<?php

namespace App\Repository;

use App\Entity\Biens;
use App\Entity\Villes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Biens>
 *
 * @method Biens|null find($id, $lockMode = null, $lockVersion = null)
 * @method Biens|null findOneBy(array $criteria, array $orderBy = null)
 * @method Biens[]    findAll()
 * @method Biens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biens::class);
    }

    public function save(Biens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Biens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Biens[] Returns an array of Biens objects
     */
    public function findAllFieldPaginated($values = null): QueryBuilder
    {
        if ($values != null) {
            $type = $values->getType();
            if ($type == 'A Louer') {
                $type = 1;
            } elseif ($type == 'A Vendre') {
                $type = 0;
            }
            $typeBien = $values->getTypeBien();
            $villes = $values->getVille();
            if ($villes != 'Choisir une ville' && !empty($villes)){
                foreach ($villes as $ville) {
                    $ville_id[] = $ville->getId();
                }
            }
            $prix = $values->getPrix();
            if ($prix != 'Prix/DT'){
                preg_match_all('!\d+!', $prix, $matches);
                if (isset($matches[0][1])){
                    $prix_min = $matches[0][0];
                    $prix_max = $matches[0][1];
                }else{
                    $prix_min = $matches[0][0];
                }

            }
            $ref = $values->getRef();
        }


        $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.id','desc');
        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        if ($values != null) {
            if ($type == 1 || $type == 0){
                $criteria->where($expressionBuilder->eq('type', $type));
            }
            if ($typeBien != 'Types Du Bien' && !empty($typeBien)) {
                $criteria->Andwhere($expressionBuilder->in('typeBien', $typeBien));
            }

            if ($villes != 'Choisir une ville' && !empty($villes)) {
                $criteria->Andwhere($expressionBuilder->in('ville', $ville_id));
            }
            if ($prix != 'Prix/DT') {
                $criteria->Andwhere($expressionBuilder->gte('prix', $prix_min));
                if (isset($matches[0][1])) {
                    $criteria->Andwhere($expressionBuilder->lte('prix', $prix_max));
                }
            }
            if ($ref != null){
                $criteria->Andwhere($expressionBuilder->eq('ref', $ref));
            }
        }

        $queryBuilder->addCriteria($criteria);
//        dd($queryBuilder);

        return $queryBuilder;

    }


    /**
     * @return Biens[] Returns an array of Biens objects
     */
    public function findAllFieldPaginatedwithparams($values = null, $type = null): QueryBuilder
    {
        if ($values != null) {
            $type = $values->getType();
            if ($type == 'A Louer') {
                $type = 1;
            } elseif ($type == 'A Vendre') {
                $type = 0;
            }
            $typeBien = $values->getTypeBien();
            $villes = $values->getVille();
            if ($villes != 'Choisir une ville' && !empty($villes)){
                foreach ($villes as $ville) {
                    $ville_id[] = $ville->getId();
                }
            }
            $prix = $values->getPrix();
            if ($prix != 'Prix/DT') {
                preg_match_all('!\d+!', $prix, $matches);
                if (isset($matches[0][1])) {
                    $prix_min = $matches[0][0];
                    $prix_max = $matches[0][1];
                } else {
                    $prix_min = $matches[0][0];
                }

            }
            $pieces = $values->getPieces(); // Nombre de Pièces

            $surface = $values->getSurface();

            if ($surface != 'Surfaces m2') {
                if ($surface == 1) {
                    $surface_min = 0;
                    $surface_max = 50;
                } elseif ($surface == 2) {
                    $surface_min = 50;
                    $surface_max = 100;
                } elseif ($surface == 3) {
                    $surface_min = 100;
                    $surface_max = 150;
                } elseif ($surface == 4) {
                    $surface_min = 150;
                    $surface_max = 200;
                } elseif ($surface == 5) {
                    $surface_min = 200;
                    $surface_max = 300;
                } elseif ($surface == 6) {
                    $surface_min = 300;
                    $surface_max = null;
                }
            }
            $neuf = $values->getNeuf();
            $ref = $values->getRef();
        }

        $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.id','desc');
        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        $criteria->where($expressionBuilder->eq('type', $type));
        if ($values != null) {
            if ($typeBien != 'Types Du Bien' && !empty($typeBien)) {
                $criteria->Andwhere($expressionBuilder->in('typeBien', $typeBien));
            }
            if ($villes != 'Choisir une ville' && !empty($villes)) {
                $criteria->Andwhere($expressionBuilder->in('ville', $ville_id));
            }
            if ($prix != 'Prix/DT') {
                $criteria->Andwhere($expressionBuilder->gte('prix', $prix_min));
                if (isset($matches[0][1])) {
                    $criteria->Andwhere($expressionBuilder->lte('prix', $prix_max));
                }
            }
            if ($pieces != 'Nombre de Pièces') {
                $criteria->Andwhere($expressionBuilder->eq('pieces', $pieces));
            }

            if ($surface != 'Surfaces m2') {
                $criteria->Andwhere($expressionBuilder->gte('surface', $surface_min));
                if ($surface_max != null) {
                    $criteria->Andwhere($expressionBuilder->lte('surface', $surface_max));
                }
            }
            if ($neuf != 'Bien neuf') {
                $criteria->Andwhere($expressionBuilder->eq('neuf', $neuf));
            }
            if ($ref != null){
                $criteria->Andwhere($expressionBuilder->eq('ref', $ref));
            }
        }
        $queryBuilder->addCriteria($criteria);

        return $queryBuilder;

    }

//    public function findOneBySomeField($value): ?Biens
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
