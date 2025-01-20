<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VisiteRepository;

class VoyagesController extends AbstractController {
    /**
     * @var VisiteRepository
     */
    private $repository;

    /**
     * @param VisiteRepository $repository
     */
    public function __construct(VisiteRepository $repository) {
        $this->repository = $repository;
    }

    #[Route('/voyages', name: 'voyages')]
    public function index(): Response {
        // Récupérer les données depuis la base
        $visites = $this->repository->findAll();

        // Envoyer les données à la vue
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visites
        ]);
    }
}
