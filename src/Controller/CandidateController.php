<?php

namespace App\Controller;

use App\Entity\Candidates;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateController extends AbstractController
{
    /**
     * @Route("/candidate", name="candidate")
     */
    public function index(): Response
    {
        return $this->render('candidate/index.html.twig', [
            'controller_name' => 'CandidateController',
        ]);
    }

    /**
     * @Route("/candidate/{id}", name="candidate_show", options={"expose"=true})
     */
    public function show(Candidates $candidate): Response
    {
        return $this->render('candidate/show.html.twig', [
            'candidate' => $candidate,
        ]);
    }

    /**
     * @Route("/candidate/{id}", name="candidate_delete", options={"expose"=true}, methods={"DELETE"})
     */
    public function delete(Candidates $candidate): Response
    {



        return $this->json([]);
    }


}
