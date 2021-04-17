<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

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

    public function show(Program $program):Response
    {

        $seasons = $this->getDoctrine()->getRepository(Season::class)->findBy(['program_id' => $program->getId()]);

        if(!$program){
            throw $this->createNotFoundException('No program with id : '.$program->getId().' found in program\'s table.');
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * @Route("/programs/{programId}/season/{seasonId}", name="program_season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * 
     * 
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {

        if(!$program){
            throw $this->createNotFoundException('No program with id : ' . $program->getId() . ' found in program\'s table.');
        }

        if(!$season){
            throw $this->createNotFoundException('No season with id : ' . $season->getId() . ' found in season\'s table.');
        }
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season
        ]);
    }

    /**
     * @Route("/programs/{programId}/seasons/{seasonId}/episodes/{episodeId}", name="program_episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
     * 
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        if(!$program){
            throw $this->createNotFoundException('No program with id : ' . $program->getId() . ' found in program\'s table.');
        }

        if(!$season){
            throw $this->createNotFoundException('No season with id : ' . $season->getId() . ' found in season\'s table.');
        }

        if (!$episode) {
            throw $this->createNotFoundException('No season with id : ' . $episode->getId() . ' found in episode\'s table.');
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);
    }

    /**
     * @Route("/add-program", name="new_program")
     */
    public function new(Request $request): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){

            $this->addFlash('notice', 'Your program has been saved !');

            $em = $this->getDoctrine()->getManager();
            $em->persist($program);
            $em->flush();

            return $this->redirectToRoute('program');
        }

        return $this->render('program/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
