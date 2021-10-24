<?php

namespace App\Repository;

use App\Entity\Candidates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Candidates|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidates|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidates[]    findAll()
 * @method Candidates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidates::class);
    }

    use DataTableProcessingTrait;

    // /**
    //  * @return Candidates[] Returns an array of Candidates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Candidates
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
//    public function processingCandidate($start, $max, $searching = [], $ordering=null)
//    {
//        $qb = $this->createQueryBuilder('q')
//            ->setFirstResult($start)
//            ->setMaxResults($max)
//            ;
//
//        if (!empty($searching) && $searching['search'] && !empty($searching['fieldsForSearching'])){
//            foreach ($searching['fieldsForSearching'] as $index => $field) {
//                $qb->orWhere('q.'.$field.' like :search');
//            }
//            $qb->setParameter('search', $searching['search'].'%');
//        }
//
//        if ($ordering){
//            $qb->orderBy('q.'.$ordering['field'],$ordering['dir'] );
//        }
//
//
//        return $qb->getQuery()
//            ->getResult()
//            ;
//    }
}
