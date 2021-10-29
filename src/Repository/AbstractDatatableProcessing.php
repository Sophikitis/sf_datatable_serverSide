<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractDatatableProcessing extends ServiceEntityRepository
{

    /**
     * Retour un tableau contenant le total
     *
     * @param $start
     * @param $max
     * @param array $searching
     * @param null $ordering
     * @return array
     */
    public function processing($start, $max, array $searching = [], $ordering=null): array
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
            // INSERT CUSTOM SEARCH WITH ABSTRACT METHOD
            $this->customSearchProcessing($qb);

            $qb->setParameter('search', $searching['search'].'%');
        }


        if ($ordering){
            $qb->orderBy('q.'.$ordering['field'],$ordering['dir'] );
        }

        // INSERT CUSTOM REQUEST WITH ABSTRACT METHOD
        $this->customRequestProcessing($qb);

        // recuperation des données filtrées
        $data = $qb->getQuery()->getResult();

        // recuperation du nombre d'elements filtrés si on a une valeur de recherche
        if (!empty($searching)){
            $qb->select('count(q.id)');
            $qb->setFirstResult(null);
            $total = $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        }

        return [
            'recordsFiltered' => isset($total) ? (int)$total : null,
            'data'=> $data
        ];

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