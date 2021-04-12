<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{
    /**
     * @Route("/programs", name="program")
     */
    public function index(): Response
    {
        return $this->render('programs/index.html.twig', [
            'controller_name' => 'ProgramController',
        ]);
    }

    /**
     * Route("/programs/{id}", requirements={"page"="\d+"}, methods={"GET"}, name="program_show")
     */
    public function show(int $id = 4): Response
    {
        
        return $this->render('programs/show.html.twig', [
            'id' => $id
        ]);
    }
}
