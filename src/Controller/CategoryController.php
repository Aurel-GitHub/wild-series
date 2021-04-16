<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        

        if (!$categories) {
            throw $this->createNotFoundException('No category found ! ');
        }

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     *
     * @Route("/category/{name}", name="category_show")
     */
    public function show(string $name): Response
    {
        if (!$name) {
            throw $this->createNotFoundException('No category found ! ');
        } else {
            $categoryName = $this->getDoctrine()->getRepository(Category::class)->findOneByName($name);

            $categories = $this->getDoctrine()->getRepository(Program::class)->findBy(['category' => $categoryName->getId()], ['id' => 'DESC'], 5);
            
            return $this->render('category/show.html.twig', [
               'categories' => $categories,
               'category_name' => $categoryName
           ]);
        }
    }

    /**
     * @Route("/new", name="new_category")
     */
    public function new(): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        return $this->render('category/new.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
