<?php

namespace App\Controller;

use App\Entity\Candidates;
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


}
