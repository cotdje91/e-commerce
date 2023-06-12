<?php

namespace App\Manager;

use App\Entity\Users;
use App\Entity\Orders;
use App\Entity\Products;
use App\Service\CartService;
use App\Service\StripePaymentService;
use Doctrine\ORM\EntityManagerInterface;

class ProductsManager
{
    protected $em;
    protected $StripePaymentService;
    public function __construct(EntityManagerInterface $entityManger, StripePaymentService $stripePaymentService)
    {
        $this->em = $entityManger;
        $this->StripePaymentService = $stripePaymentService;

    }
    public function getProducts()
    {
        return $this->em->getRepository(Products::class)->findAll();
    }
    public function countBySoldeOrder(Users $users)
    {
        return $this->em->getRepository(Orders::class)
            ->countBySoldeOrder($users);
    }

    public function getOrders(Users $users)
    {
        return $this->em->getRepository(Orders::class)
            ->findByUsers($users);
    }

    public function intentSecret(Products $products)
    {
        $intent = $this->StripePaymentService->paymentIntent($products);

        return $intent['client_secret'] ?? null;
    }
    public function stripe(array $stripeParameter, $products): array
    {
        $resource = [];
        $data = $this->StripePaymentService->stripe($stripeParameter, $products);
        // dump($data);
        // dd($data);
        // dd($data->payment_method_options->card->request_three_d_secure);
        // dd($data->payment_method_types[0]);
        if($data){
            // -> notation objet
            // si objet implement interface ArrayAccess je peux y acceder avec [] ex ($data['charges']['data']);
            $resource = [
                'stripeId' => $data->id,
                'stripeLast4'=> '0000',
                'stripeToken' => $data->client_secret,
                'stripeStatus' =>$data->status,
                'stripeBrand' => ''
            ];
        }
        return $resource;
    }

    // public function create_subscription(array $resource, Products $products, Users $users)
    // {
    //     $orders = new Orders();

    //     $orders->setUsers($users);
    //     $orders->setProducts($products);
    //     $orders->setPrice($products->getPrice());
    //     $orders->setReference(uniqid('', false));
    //     $orders->setBrandStripe($resource['stripeBrand']);
    //     $orders->setLast4Stripe($resource['stripeLast4']);
    //     $orders->setIdChargeStripe($resource['stripeId']);
    //     $orders->setStripeToken($resource['stripeToken']);
    //     $orders->setStatusStripe($resource['stripeStatus']);
    //     $orders->setUpdatedAt(new \DateTimeImmutable());
    //     $orders->setCreatedAt(new \DateTimeImmutable());
    //     $this->em->persist($orders);
    //     $this->em->flush();


    // }
}