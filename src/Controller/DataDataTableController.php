<?php

namespace App\Controller;

use App\Repository\CandidatesRepository;
use App\Services\DataTableProcessingAjaxService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataDataTableController extends AbstractController
{
    /**
     * @Route("/data", name="data_datatable")
     */
    public function index(Request $request, CandidatesRepository $candidatesRepository, DataTableProcessingAjaxService $dataTableProcessingAjax): Response
    {
        $output = $dataTableProcessingAjax->serverSide($request, $candidatesRepository);
        return $this->json($output, 200, [], ['groups' => ['dt_actions','dtCandidate','dtRowId', 'dtRowClass']]);
    }







}
