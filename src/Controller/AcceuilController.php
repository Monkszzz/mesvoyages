<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController {
    
    #[Route('/', name: 'accueil')]
    public function index(): Response {
        return $this->render("pages/acceuil.html.twig");
    }
}

