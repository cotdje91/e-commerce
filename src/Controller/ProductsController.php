<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//produits
#[Route('/produits', name: 'products_')]

class ProductsController extends AbstractController

{

    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {

        return $this->render('products/index.html.twig',[
            'categories'=> $categoriesRepository->findBy([],
            ['categoryOrder'=> 'asc'])
        ]);
    }

    // {} pour dire que c'est variable 
    #[Route('/{slug}', name: 'details')]
    public function details(Products $product ): Response
    {
        // dd($products->getDescription());
        // compact pour crer un tableau
        return $this->render('products/details.html.twig', compact('product'));
    }
}

