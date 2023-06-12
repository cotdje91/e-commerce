<?php

namespace App\Controller;


// use App\Entity\Products;

use App\Form\OrderType;
use App\Entity\Products;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    // creation de fonction annexe
    #[Route('/mon-panier', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getTotal(),
            // 'recapCart' => $cartService->getTotal(),
            'form' => $form->createView()
            // compact('product')
            // 'product' => $product
            
        ]); 
    }

    // {id<\d+>} pour autoriser que les chaine de caractere
    #[Route('/mon-panier/add/{id<\d+>}', name: 'cart_add')]
    public function addToCart(CartService $cartService, int $id): Response
    {
        $cartService->addToCart($id);

        return $this->redirectToRoute('cart_index');
    }


    #[Route('/mon-panier/remove/{id<\d+>}', name: 'cart_remove')]
    public function removeToCart(CartService $cartService, int $id): Response
    {
        $cartService->removeToCart($id);

        return $this->redirectToRoute('cart_index');
    }

    // fuction pour decrementé cad : enlevier un article (dans mon cas)
    #[Route('/mon-panier/decrease/{id<\d+>}', name: 'cart_decrease')]
    public function decrease(CartService $cartService, int $id, Products $products): RedirectResponse
    {
        $cartService->decrease($id);

        return $this->render('cart/index.html.twig',[
            'recapCart' => $cartService->getTotal(),
            'products' => $products
        ]);
    }



    #[Route('/mon-panier/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService): Response
    {
        $cartService->removeCartAll();
        return $this->redirectToRoute('products_index');
    }

}
