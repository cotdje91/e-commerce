<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'profil_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }
//order = commande
    #[Route('/commandes', name: 'order_create')]
    public function orders(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'commandes de l\'utilisateur',
        ]);
    }
}
