<?php

namespace App\Controller;

use App\Entity\Candidates;
use App\Repository\CandidatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            'controller_name' => 'CandidateController'
        ]);
    }

    /**
     * @Route("/candidate/{id}", name="candidate_show", options={"expose"=true}, methods={"GET", "POST"})
     */
    public function show(Candidates $candidate): Response
    {
        dump('ok');
        return $this->render('candidate/show.html.twig', [
            'candidate' => $candidate,
        ]);
    }



    /*AJAX


    _____________________________*/


    /**
     * @Route("/candidate/{id}", name="candidate_delete", options={"expose"=true}, methods={"DELETE"})
     */
    public function delete(Candidates $candidate, Request $request, EntityManagerInterface $entityManager): Response
    {
        $token = $request->request->get('CSRF');

        if ($this->isCsrfTokenValid('delete'.$this->getUser()->getUsername(), $token)) {
            $entityManager->remove($candidate);
            $entityManager->flush();

            return $this->json(['success'], 200);
        }

        return $this->json(['error'], 400);
    }


    // BETA
    /**
     * @Route("/candidate/detail/ajax", name="candidate_detail_dt", options={"expose"=true}, methods={"POST"})
     */
    public function detail(Request $request, CandidatesRepository $candidatesRepository): Response
    {
        $id = $request->request->get('id');
        $can = $candidatesRepository->find($id);

        return $this->json($can, 200, [], ['groups' => 'candidate_tags_read']);
    }


}
