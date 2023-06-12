<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Entity\Products;
use App\Service\CartService;
use App\Manager\ProductsManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrdersController extends AbstractController
{
    #[Route('/order/create', name: 'order_create', methods:["GET", "POST"])]
    public function index(CartService $cartService): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'recapCart' => $cartService->getTotal(),
        ]);
    }

    #[Route('/users/payment/{id}/show', name: 'payment_add', methods:["GET", "POST"])]
    public function payer(CartService $cartService, Products $products, ProductsManager $productsManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('users/payment.html.twig', [
            'users' => $this->getUser(),
            'intentSecret' => $productsManager->intentSecret($products),
            'recapCart' => $cartService->getTotal(),
            'products' => $products
        ]);
    }

    #[Route('/users/subscription/{id}/paiement/load', name: 'subscription_paiement', methods:["GET", "POST"])]
    public function subscription(Products $products, Request $request, ProductsManager $productsManager, CartService $cartService): Response
    {
        $users = $this->getUser();

        if($request->getMethod() === 'POST'){
            // Utiliser que ce dont on a besoin
            $stripeParameter = [
                'stripeIntentId'=>$request->request->get('stripeIntentId'),
                'stripeIntentStatus'=>$request->request->get('stripeIntentStatus')
            ];
            $resource = $productsManager->stripe($stripeParameter, $products, $cartService);

            if(null !== $resource){
                $productsManager->create_subscription($resource, $products, $users);

                return $this->render('users/reponse.html.twig', [
                    'products' => $products,
                'recapCart' => $cartService->getTotal(),

                ]);
            }
        }
        return $this->redirectToRoute('payment', ['id' => $products->getId()]);
    }

    #[Route('/users/payment/orders', name: 'payment_orders', methods:["GET", "POST"])]
    public function payment_orders(ProductsManager $productsManager, CartService $cartService): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('users/payment_story.html.twig', [
            'users' => $this->getUser(),
            'orders' => $productsManager->getOrders($this->getUser()),
            'sumOrder' => $productsManager->countBySoldeOrder($this->getUser()),
            'recapCart' => $cartService->getTotal()

        ]);
    }
}
