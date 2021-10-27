<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractDatatableProcessing extends ServiceEntityRepository
{

    public function processing($start, $max, $searching = [], $ordering=null)
    {
        $qb = $this->createQueryBuilder('q')
            ->setFirstResult($start)
            ->setMaxResults($max)
        ;

        if (!empty($searching) && $searching['search'] && !empty($searching['fieldsForSearching'])){
            foreach ($searching['fieldsForSearching'] as $index => $field) {
                // Si on a oublié de nommer la column, la valeur sera vide, afin d'eviter une erreur on fait une verification
                if (!empty($field)){
                    $qb->orWhere('q.'.$field.' like :search');
                }
            }

            $this->customSearchProcessing($qb);

            $qb->setParameter('search', $searching['search'].'%');
        }


        if ($ordering){
            $qb->orderBy('q.'.$ordering['field'],$ordering['dir'] );
        }

        $this->customRequestProcessing($qb);


        return $qb->getQuery()
            ->getResult()
            ;
    }

    /**
     * Permet d'ajouter des conditions specifiques a la requete de base

     * si aucun traitement est desiré ajouter simplement un return
     * @param QueryBuilder $qb
     */
    abstract protected function customRequestProcessing(QueryBuilder $qb);

    /**
     * Permet de faire des recherches custom et supplementaires
     *
     * Par exemple : $qb->orWhere('q.firstname' like :search');
     *
     * si aucun traitement est desiré ajouter simplement un return
     * @param QueryBuilder $qb
     */
    abstract protected function customSearchProcessing(QueryBuilder $qb);


}