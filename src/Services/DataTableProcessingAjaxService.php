<?php

namespace App\Services;

use App\Repository\AbstractDatatableProcessing;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class DataTableProcessingAjaxService
{

    public function serverSide(Request $request, AbstractDatatableProcessing $repository): array
    {
        if ($request->isMethod('POST') && $request->isXmlHttpRequest()) {

            // get request elements
            $draw = (int)$request->request->get('draw');
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');
            // options request init
            $searching = ['search' => null, 'fieldsForSearching' => []];
            $ordering = null;


            /* SEARCH PART

            Verifie qu'il y'a une valeur dans $search, si c'est le cas on va boucler
            sur toutes les colonnes afin de verifier si la colonne est autorisé a etre
            filtrer par la fonction search.

            Si elle l'est, on recupere le nom de la colonne qui doit etre iso au champs de l'entité,
            et on l'ajoute on tableau des champs sur lequelle on va faire la recherhche
             * */
            if (!empty($search['value'])) {
                $searching['search'] = $search['value'];
                foreach ($columns as $column) {
                    // get name field on searchable is enabled
                    if ($column['searchable'] === 'true') {
                        $searching['fieldsForSearching'][] = $column['name'];
                    }
                }
            }

            /*ORDER PART*/
            foreach ($orders as $order) {
                if (array_key_exists($order['column'], $columns)) {
                    $column = $columns[$order['column']];
                    if ($column['orderable'] === 'true') {
                        $ordering = ['field' => $column['name'], 'dir' => strtoupper($order['dir'])];
                    }
                }
            }

            $entity = $repository->processing($start, $length, $searching, $ordering);

            $recordsTotal = $repository->count([]);
            $output = [
                'draw' => $draw,
                "recordsTotal"=> $recordsTotal,
                "recordsFiltered"=> $entity['recordsFiltered'] ?? $recordsTotal,
                'data' => $entity['data'],
            ];

        }else{
            $output=['error' => 'Method is not allowed'];
        }

        return $output;
    }

}