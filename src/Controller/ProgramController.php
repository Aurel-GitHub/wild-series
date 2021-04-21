<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @Route("/show/{program_slug}", name="program_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_slug": "slug"}})
     * @return Response
     */

    public function show(Program $program):Response
    {

        $seasons = $this->getDoctrine()->getRepository(Season::class)->findBy(['program_id' => $program->getId()]);

        if(!$program){
            throw $this->createNotFoundException('No program with id : '.$program->getSlug().' found in program\'s table.');
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * @Route("/programs/{program_slug}/season/{season_number}", name="program_season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_slug": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_number": "number"}})
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
            'season' => $season,
            'episodes' => $season->getEpisodes()
        ]);
    }

    /**
     * @Route("/programs/{program_slug}/seasons/{season_number}/episodes/{episode_slug}", name="program_episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_slug": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_number": "number"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_slug": "slug"}})
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
    public function new(Request $request,  Slugify $slugify, MailerInterface $mailer): Response
    {
            $program = new Program();

            $form = $this->createForm(ProgramType::class, $program);
    
            $form->handleRequest($request); 
    
            if($form->isSubmitted() && $form->isValid()){
    
                $program->setSlug($slugify->generate($program->getTitle()));
                $program->setOwner($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($program);
                $em->flush();
                
    
                $email = (new Email())
                ->from('doe@yopmail.com')
                ->to('doe@yopmail.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('email/program.html.twig', ['program' => $program]));
    
                $mailer->send($email);
    
                return $this->redirectToRoute('program');
            }

        return $this->render('program/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/programs/{slug}/edit", name="program_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Program $program)
    {
            $form = $this->createForm(ProgramType::class, $program);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('program');
            }

            if (!($this->getUser() == $program->getOwner())) {
                throw new AccessDeniedException('Only the owner can edit the program!');
            }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/program/{id}", name="program_delete", methods={"POST"})
     */
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program');
    }
}

