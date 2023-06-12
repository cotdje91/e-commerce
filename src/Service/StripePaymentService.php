<?php

namespace App\Service;

// use App\Entity\Address;
use Stripe\Stripe;
use App\Entity\Orders;
use App\Entity\Products;
use Stripe\PaymentIntent;
use App\Service\CartService;
// use App\Repository\OrdersRepository;

class StripePaymentService
{
    private $privateKey;

    public function __construct(){
        if($_ENV['APP_ENV'] === 'dev'){
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        }else{
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        }
    }

    public function paymentIntent(Products $products)
    {
        Stripe::setApiKey($this->privateKey);

        return PaymentIntent::create([
            'amount' => $products->getPrice() * 100,
            'currency' => Orders::DEVISE,
            'payment_method_types' => ['card']
        ]);
    }

    public function  paiement(
        $amount,
        $currency, 
        $description, 
        array $stripeParameter
    )
    {
        Stripe::setApiKey($this->privateKey);
        $payment_intent = null;
        if(isset($stripeParameter['stripeIntentId'])){
            $payment_intent = PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }
        if($stripeParameter['stripeIntentStatus'] === 'succeeded'){
            // TODO
        } else {
            $payment_intent->cancel();
        }
        return $payment_intent;
    }
    public function stripe(array $stripeParameter, Products $products)
    {
        return $this->paiement(
            $products->getPrice() / 100,
            Orders::DEVISE,
            $products->getName(),
            $stripeParameter
        );
    }

    
    // public function persistBidule(Orders $orders, OrdersRepository $ordersRepository): void{
    //     $orders = new Orders();

    //     $this->paymentIntent

    //     $orders->setName('toto')
    //             ->setAge(12);

    //     $ordersRepository->save(
    //         entity: $orders,
    //         flush: true
    //     );
    // }

}