<?php

namespace App\Repository;

use App\Entity\Candidates;

use App\Entity\Tags;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Hcnx\DatatableProcessingBundle\Repository\AbstractDatatableProcessing;

/**
 * @method Candidates|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidates|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidates[]    findAll()
 * @method Candidates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatesRepository extends AbstractDatatableProcessing
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidates::class);
    }

    protected function customRequestProcessing(QueryBuilder $qb):void
    {
        return;
    }

    protected function customSearchProcessing(QueryBuilder $qb):void
    {
        $qb->leftJoin(Tags::class, 't', 'WITH', 'q.id = t.candidates');
        $qb->orWhere('t.value like :search');
        $qb->orWhere('t.tag like :search');
    }
}
