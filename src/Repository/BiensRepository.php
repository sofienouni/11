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
            if ($prix != 'Prix/DT' && $prix != null){
                preg_match_all('!\d+!', $prix, $matches);
                if (isset($matches[0][1])){
                    $prix_min = $matches[0][0];
                    $prix_max = $matches[0][1];
                }else{
                    $prix_min = $matches[0][0];
                }

            }
            $ref = $values->getRef();
            $order = $values->getTrie();
        }

        if (isset($order)){
            if ($order == 'prix+'){
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.prix','asc');
            }
            elseif ($order == 'prix-'){
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.prix','desc');
            }
            elseif ($order == 'surface+'){
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.surface','asc');
            }
            elseif ($order == 'surface-'){
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.surface','desc');
            }else{
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.id','desc');
            }
        }else {
            $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.id','desc');
        }

        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        if ($values != null) {
            if ($type != null && ($type == 1 || $type == 0)){
                $criteria->where($expressionBuilder->eq('type', $type));
            }
            if ($typeBien != 'Types Du Bien' && !empty($typeBien)) {
                $criteria->Andwhere($expressionBuilder->in('typebien', $typeBien));
            }

            if ($villes != 'Choisir une ville' && !empty($villes)) {
                $criteria->Andwhere($expressionBuilder->in('ville', $ville_id));
            }
            if ($prix != 'Prix/DT' && $prix != null) {
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


        return $queryBuilder;

    }


    /**
     * @return Biens[] Returns an array of Biens objects
     */
    public function findAllFieldPaginatedwithparams($values = null, $type = null): QueryBuilder
    {

        if ($values != null) {
            if ($type == null) {
                $type = $values->getType();
                if ($type == 'A Louer') {
                    $type = 1;
                } elseif ($type == 'A Vendre') {
                    $type = 0;
                }
            }
            $typeBien = $values->getTypeBien();
            $villes = $values->getVille();
            if ($villes != 'Choisir une ville' && !empty($villes)){
                foreach ($villes as $ville) {
                    $ville_id[] = $ville->getId();
                }
            }
            $prix = $values->getPrix();
            if ($prix != 'Prix/DT' && $prix != null) {
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
            $ref = $values->getRef();
            $order = $values->getTrie();
        }
        if (isset($order)){
            if ($order == 'prix+'){
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.prix','asc');
            }
            elseif ($order == 'prix-'){
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.prix','desc');
            }
            elseif ($order == 'surface+'){
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.surface','asc');
            }
            elseif ($order == 'surface-'){
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.surface','desc');
            }else{
                $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.id','desc');
            }
        }else {
            $queryBuilder = $this->createQueryBuilder('b')->orderBy('b.id','desc');
        }

        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        $criteria->where($expressionBuilder->eq('type', $type));
        if ($values != null) {
            if ($typeBien != 'Types Du Bien' && !empty($typeBien)) {
                $criteria->Andwhere($expressionBuilder->in('typebien', $typeBien));
            }
            if ($villes != 'Choisir une ville' && !empty($villes)) {
                $criteria->Andwhere($expressionBuilder->in('ville', $ville_id));
            }
            if ($prix != 'Prix/DT' && $prix != null) {
                $criteria->Andwhere($expressionBuilder->gte('prix', $prix_min));
                if (isset($matches[0][1])) {
                    $criteria->Andwhere($expressionBuilder->lte('prix', $prix_max));
                }
            }
            if ($pieces != 'Nombre de Pièces') {
                $criteria->Andwhere($expressionBuilder->in('pieces', $pieces));
            }

            if ($surface != 'Surfaces m2') {
                $criteria->Andwhere($expressionBuilder->gte('surface', $surface_min));
                if ($surface_max != null) {
                    $criteria->Andwhere($expressionBuilder->lte('surface', $surface_max));
                }
            }

            if ($ref != null){
                $criteria->Andwhere($expressionBuilder->eq('ref', $ref));
            }
        }
        $queryBuilder->addCriteria($criteria);

        return $queryBuilder;

    }

    /**
     * @return Biens[] Returns an array of Images objects
     */
    public function findByExampleField($value = null): array
    {
        if (isset($value['current'])) {
            return $this->createQueryBuilder('b')
                ->andWhere('b.typebien = :val')
                ->andWhere('b.type = :val1')
                ->andWhere('b.id != :val2')
                ->setParameter('val', $value['type_bien'])
                ->setParameter('val1', $value['type'])
                ->setParameter('val2', $value['current'])
                ->orderBy('b.id', 'DESC')
                ->setMaxResults(6)
                ->getQuery()
                ->getResult();
        }else{
            return $this->createQueryBuilder('b')
                ->andWhere('b.typebien = :val')
                ->andWhere('b.type = :val1')
                ->setParameter('val', $value['type_bien'])
                ->setParameter('val1', $value['type'])
                ->orderBy('b.id', 'DESC')
                ->setMaxResults(6)
                ->getQuery()
                ->getResult();
        }
    }

}
