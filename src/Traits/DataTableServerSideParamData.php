<?php

namespace App\Traits;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Ajoute a une entité des parametres optionnel lors de la reponse du processing
 *  ___________________________________________________
 * DOC : https://datatables.net/manual/server-side
 * ____________________________________________________
 *
 * dans la classe ou on use le trait, il est possible de surcharger les fonctions get afin
 * de retourné la donnée que l'on souhaite.
 *
 * on peut rajouter un context dans le this->json() afin de selectionner la donnée que l'on
 * souhaite afficher.
 *
 * par exemple si on souhaite uniquement le DT_RowId :
 * return $this->json($output, 200, [], ['groups' => ['dtCandidate','dtRowId']]);
 *
 * Ou si on souhaite avoir tous les params du traits, on utilisera le groups 'dtFull'
 *
 * ATTENTION :
 * ---------
 * Du moment ou on utilise les contexts, il faudra sur l'entité en question utilisé l'annoation
 * des groups afin de selectionner les champs que l'on souhaite afficher.
 *
 * exemple :
 *
 *  * @ Groups({"dtCandidate", "dtRowId"})
 *    private $id;
 *

 *
 */
trait DataTableServerSideParamData
{


    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * @Groups({"dtRowId", "dtFull"})
     */
    private $DT_RowId;

    /**
     * @Groups({"dtRowClass", "dtFull"})
     */
    private $DT_RowClass;

    /**
     * @Groups({"dtRowData", "dtFull"})
     */
    private $DT_RowData;

    /**
     * @Groups({"dtRowAttr", "dtFull"})
     */
    private $DT_RowAttr;

    public function getDTRowId():int
    {
        return $this->id;
    }

    public function getDTrowClass():string
    {
        return 'dt_custom_tr_css';
    }

    public function getDTRowData():array
    {
        return [];
    }

    public function getDTrowAttr():array
    {
        return [];
    }

}