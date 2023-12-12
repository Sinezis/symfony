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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\ProgramDuration;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{ 
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $programs = $programRepository->findAll();
        return $this->render(
            'program/index.html.twig', [
            'programs' => $programs
         ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, SluggerInterface $slugger) : Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush(); 
            
            $email = (new Email())
            ->from('adrien.cremeaux@outlook.fr')
            ->to('adrien.cremeaux@outlook.fr')
            ->subject('Une nouvelle série vient d\'être publiée !')
            ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);
            
            $this->addFlash('success', 'The new program has been created');
    
            // Redirect to program list
            return $this->redirectToRoute('program_index');
        }
    
        // Render the form
        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{programSlug}', name: 'show')]
    public function show(
        #[MapEntity(mapping: ['programSlug' => 'slug'])] Program $program, ProgramDuration $programDuration
        ): Response
        // same as $program = $programRepository->find($id);
        {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program->getSlug() . 'found in program\'s table'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program)
        ]);
    }

    #[Route('/{programSlug}/season/{seasonId}', name: 'season_show')]
    public function showSeason(
        #[MapEntity(mapping: ['programSlug' => "slug"])] Program $program, 
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
        #[MapEntity(mapping: ['programSlug' => "slug"])] Program $program,
        #[MapEntity(mapping: ['seasonID' => "id"])] Season $season,
        #[MapEntity(mapping: ['episodeSlug' => "slug"])] Episode $episode
    ): Response
    {
        return $this->render('program/episode_show.html.twig', ['season' => $season, "program" => $program, "episode" => $episode]);
    }

    #[Route('/{programSlug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        #[MapEntity(mapping: ['programSlug' => "slug"])] Program $program,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);

            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{programSlug}', name: 'delete', methods: ['POST'])]
    public function delete(
        Request $request,
        #[MapEntity(mapping: ['programSlug' => "slug"])] Program $program, 
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'. $program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
        }
        $this->addFlash('danger', 'The program has been deleted');

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}

