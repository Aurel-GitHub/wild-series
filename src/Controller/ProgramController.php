<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{

    /**
     * Show all series
     * @Route("/program", name="program")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/show/{id}", name="program_show")
     * @return Response
     */

    public function show(int $id):Response
    {
        $program = $this->getDoctrine()->getRepository(Program::class)->findOneBy(['id' => $id]);

        $seasons = $this->getDoctrine()->getRepository(Season::class)->findBy(['program_id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id : '.$id.' found in program\'s table.');
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * @Route("/programs/{programId}/season/{seasonId}", name="program_season_show")
     */
    public function showSeason(int $programId, int $seasonId)
    {
        $program = $this->getDoctrine()->getRepository(Program::class)->findOneBy(['id' => $programId]);
        $season = $this->getDoctrine()->getRepository(Season::class)->findOneBy(['id' => $seasonId]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id : ' . $programId . ' found in program\'s table.');
        }

        if (!$season) {
            throw $this->createNotFoundException('No program with id : ' . $seasonId . ' found in season\'s table.');
        }
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }
}
