<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Repository\ProgramRepository;
use App\Form\CategoryType;
use App\Entity\Category;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController 
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render (
        'category/index.html.twig', ['categories' => $categories]
      );
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();   
            
            $this->addFlash('success', 'The new category has been created');
    
            // Redirect to categories list
            return $this->redirectToRoute('category_index');
        }
    
        // Render the form
        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
 * The controller for the category add form
 * Display the form or deal with it
 */

    #[Route('/show/{categoryName}', name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category : '.$categoryName.' found in category\'s table.'
            );
        }

        $programs = $programRepository->getCategorySeries($category);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs
        ]);
    }
}