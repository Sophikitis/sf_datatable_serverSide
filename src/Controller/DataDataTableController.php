<?php

namespace App\Controller;

use App\Repository\CandidatesRepository;
use Hcnx\DatatableProcessingBundle\Services\DatatableProcessingService;
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
    public function index(Request $request, CandidatesRepository $candidatesRepository, DatatableProcessingService $dataTableProcessingAjax): Response
    {
        $output = $dataTableProcessingAjax->serverSide($request, $candidatesRepository);
        return $this->json($output, 200, [], ['groups' => ['dtCandidate']]);
    }
}
