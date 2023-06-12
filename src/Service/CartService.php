<?php
namespace App\Service;


use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    private RequestStack $requestStack;

    private EntityManagerInterface $em;


    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;

        $this->em = $em;

    }

    // pour selectionner l'article en fonction de son id (int $id)
    // + creer notre interface
    public function addToCart(int $id): void {

        // incrementation
        $cart = $this->getSession()->get('cart',[]);
        // "!"empty = existe pas
        if(!empty($cart[$id])){
            $cart[$id]++; //++ = ajouter
        }else{
            $cart[$id] = 1;
        }
        $this->getSession()->set('cart', $cart);
    }

    public function removeToCart(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart',[]);
        unset($cart[$id]);
        return $this->getSession()->set('cart', $cart);
    }

    public function decrease(int $id)
    {
        $cart = $this->getSession()->get('cart',[]);
        if($cart[$id] > 1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        $this->getSession()->set('cart', $cart);
    }

    public function removeCartAll()
    {
        return $this->getSession()->remove('cart');
    }


    public function getTotal() : array
    {
        $cart = $this->getSession()->get('cart');
        $cartData = [];
        if($cart){
            foreach ($cart as $id => $quantity){
                $products = $this->em->getRepository(Products::class)->findOneBy(['id' => $id]);
                // incrementaion
                if (!$products){
                    $this->removeToCart($id);
                    continue;
                    // si pas de produit => supprime le produis puis continue en sortant de la boucle foreach
                }
                $cartData[] = [
                    'products' => $products,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartData;
    }

        // on utilise cette fonction car on utilisera plusieurs fois le requestStack
        private function getSession() : SessionInterface
        {
            return $this->requestStack->getSession();
        }
}