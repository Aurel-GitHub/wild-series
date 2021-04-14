<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
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

        if(!$categories) {
            throw $this->createNotFoundException('No category found ! ');
        }

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * TODO redirection sur les films ayant cette catégories
     * @Route("/category/{name}", name="category_show")
     */
    public function show($name): Response
    {
        $categoryName = $this->getDoctrine()->getRepository(Category::class)->findOneByName($name);

        $categories = $this->getDoctrine()->getRepository(Program::class)->findBy(['category' => 1], ['id' => 'DESC'], 3);

       return $this->render('category/show.html.twig', [
           'categories' => $categories,
           'category_name' => $categoryName
       ]);
    }
}
