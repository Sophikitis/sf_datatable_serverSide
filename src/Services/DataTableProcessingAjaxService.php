<?php

namespace App\Services;

use App\Repository\AbstractDatatableProcessing;
use Symfony\Component\HttpFoundation\Request;

class DataTableProcessingAjaxService
{

    public function serverSide(Request $request, AbstractDatatableProcessing $repository): array
    {
        if ($request->isMethod('POST') && $request->isXmlHttpRequest()) {
            // total row in bdd
            $totalCount = $repository->count([]);

            // get request elements
            $draw = (int)$request->request->get('draw');
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');


            /*SEARCH PART*/
            $searching = ['search' => null, 'fieldsForSearching' => []];
            // get string for searching
            if (!empty($search['value'])) {
                $searching['search'] = $search['value'];

                foreach ($columns as $index => $column) {
                    // get name field on searchable is enabled
                    if ($column['searchable'] === 'true') {
                        $searching['fieldsForSearching'][] = $column['name'];
                    }
                }
            }

            /*ORDER PART*/
            $ordering = null;
            foreach ($orders as $order) {
                if (array_key_exists($order['column'], $columns)) {
                    $column = $columns[$order['column']];
                    if ($column['orderable'] === 'true') {
                        $ordering = ['field' => $column['name'], 'dir' => strtoupper($order['dir'])];
                    }
                }
            }

            // request
            // TODO : change param searching
            $candidate = $repository->processing($start, $length, $searching, $ordering);


            // debug
            dump($draw, $start, $length, $searching, $orders, $columns, $_GET, $_POST);


            // todo recordFiltered count(all with search)
            $output = [
                'draw' => $draw ?? null,
                "recordsTotal"=> $totalCount ?? null,
                "recordsFiltered"=> $searching['search'] ? count($candidate) : $totalCount,
                'data' => $candidate,
                'error' => ''
            ];

        }else{
            $output=['error' => 'Method is not allowed'];
        }

        return $output;
    }


}