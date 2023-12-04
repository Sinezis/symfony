<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render(
            'program/index.html.twig', [
            'programs' => $programs
         ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            $entityManager->persist($program);
            $entityManager->flush();            
    
            // Redirect to program list
            return $this->redirectToRoute('program_index');
        }
    
        // Render the form
        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(
        #[MapEntity(mapping: ['id' => 'id'])] Program $program
        ): Response
        // same as $program = $programRepository->find($id);
        {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{programId}/season/{seasonId}', name: 'season_show')]
    public function showSeason(
        #[MapEntity(mapping: ['programId' => 'id'])] Program $program, 
        #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season
    ): Response {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$programId.' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No seaons with id : '.$seasonId.' found in season\'s table.'
            );
        }

        if ($season->getProgram()!= $program) {
            throw $this->createNotFoundException(
                'This season doesn\'t belong to this program'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]
        );
    }
    #[Route('/{programID}/season/{seasonID}/episode/{episodeID}', methods: ["GET"], requirements:['programID' => '\d+', 'seasonID' => '\d+', 'episodeID' => '\d+'], name: 'episode_show' )]
    public function showEpisode(
        #[MapEntity(mapping: ['programID' => "id"])] Program $program,
        #[MapEntity(mapping: ['seasonID' => "id"])] Season $season,
        #[MapEntity(mapping: ['episodeID' => "id"])] Episode $episode
    ): Response
    {
        return $this->render('program/episode_show.html.twig', ['season' => $season, "program" => $program, "episode" => $episode]);
    }
}

