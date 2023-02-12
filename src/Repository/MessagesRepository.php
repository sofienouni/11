<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Messages>
 *
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    public function save(Messages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Messages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Messages[] Returns an array of Messages objects
     */
    public function findForPager(): QueryBuilder
    {
        return $this->createQueryBuilder('m')->orderBy('m.id','desc')
        ;
    }

    /**
     * @return Messages[] Returns an array of Biens objects
     */
    public function findAllFieldPaginated($values = null): QueryBuilder
    {

        if ($values != null) {
            $nom = $values->getNom();
            $prenom = $values->getPrenom();
            $telephone = $values->getTelephone();
            $ref = $values->getRef();
        }


        $queryBuilder = $this->createQueryBuilder('m')->orderBy('m.id','desc');
        $expressionBuilder = Criteria::expr();
        $criteria = new Criteria();
        if ($values != null) {
            if ($nom != null){
                $criteria->where($expressionBuilder->eq('nom', $nom));
            }

            if ($prenom != null && !empty($prenom)) {
                $criteria->Andwhere($expressionBuilder->eq('prenom', $prenom));
            }

            if ($telephone != null && !empty($telephone)) {
                $criteria->Andwhere($expressionBuilder->eq('telephone', $telephone));
            }

            if ($ref != null && !empty($ref)) {
                $criteria->Andwhere($expressionBuilder->eq('ref', $ref));
            }
        }

        $queryBuilder->addCriteria($criteria);


        return $queryBuilder;

    }
}
