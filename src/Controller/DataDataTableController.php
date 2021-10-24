<?php

namespace App\Controller;

use App\Entity\Candidates;
use App\Repository\CandidatesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataDataTableController extends AbstractController
{


    /**
     * @IsGranted("ROLE_USER")
     * @Route("/data", name="data_datatable")
     */
    public function index(Request $request, CandidatesRepository $candidatesRepository): Response
    {
        /* TODO THINK:

        generate route by RoutingJS Or manually return in data response the path for specific actions

        ex candidate :
        -id : 1
        -firstname : foo
        -lastname : bar
        -action_show : /candidate/show/{id}
        -action_update : /candidate/update/{id}
        -action_delete : /candidate/delete/{id}
        -action_delete_token: XXXXXXXXX

        OR

        directly in entity with context specific when data is serialize in json

        candidate->getActionUpdate()

        getActionUpdate :
            generatepath
            createToken ?


         * */


        /* TODO :
        - secure access token
        - add click info :
            - click on the row -> call ajax getInfos -> display popup with informations
         * */

        dump($request);

        if ($request->isMethod('POST') && $request->isXmlHttpRequest()) {

            // total row in bdd
            $totalCount = $candidatesRepository->count([]);

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
            $candidate = $candidatesRepository->processing($start, $length, $searching, $ordering);


            // debug
//            dump($draw, $start, $length, $searching, $orders, $columns, $_GET, $_POST);



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

        return $this->json($output, 200, [], ['test']);

    }
}
