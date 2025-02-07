<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VisiteRepository;
use Symfony\Component\HttpFoundation\Request;

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
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');

        // Envoyer les données à la vue
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    #[Route('/voyages/tri/{champ}/{ordre}', name: 'voyages.sort')]
    public function sort($champ, $ordre): Response{
        $visites = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visites
        ]);
                 
    }
    
    #[Route('/voyages/recherche/{champ}', name: 'voyages.findallequal')]
    public function findAllEqual($champ, Request $request): Response{
        $valeur = $request->get("recherche");
        $visites = $this->repository->findByEqualValue($champ, $valeur);
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visites
                ]);
    }
    
    #[Route('/voyages/voyage/{id}', name: 'voyages.showone')]
    public function showOne($id): Response{
        $visite = $this->repository->find($id);
        return $this->render("pages/voyage.html.twig", [
            'visite' => $visite
        ]);
    }
}