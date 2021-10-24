<?php

namespace App\Repository;

trait DataTableProcessingTrait
{

    public function processing($start, $max, $searching = [], $ordering=null)
    {
        $qb = $this->createQueryBuilder('q')
            ->setFirstResult($start)
            ->setMaxResults($max)
        ;

        if (!empty($searching) && $searching['search'] && !empty($searching['fieldsForSearching'])){
            foreach ($searching['fieldsForSearching'] as $index => $field) {
                $qb->orWhere('q.'.$field.' like :search');
            }
            $qb->setParameter('search', $searching['search'].'%');
        }

        if ($ordering){
            $qb->orderBy('q.'.$ordering['field'],$ordering['dir'] );
        }

//        dd($qb->getDQL());

        return $qb->getQuery()
            ->getResult()
            ;
    }


}