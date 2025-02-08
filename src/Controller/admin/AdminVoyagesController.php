<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\VisiteRepository;
use App\Form\VisiteType;

class AdminVoyagesController extends AbstractController {
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

    #[Route('/admin', name: 'admin.voyages')]
    public function index(): Response {
        // Récupérer les données depuis la base
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');

        // Envoyer les données à la vue admin
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    #[Route('/admin/suppr/{id}', name: 'admin.voyage.suppr')]
    public function suppr(int $id): Response{
        $visite = $this->repository->find($id);
        $this->repository->remove($visite);
        return $this->redirectToRoute('admin.voyages');
    }
    
    #[Route('/admin/edit/{id}', name: 'admin.voyage.edit')]
    public function edit(int $id, Request $request): Response{
        $visite = $this->repository->find($id);
        $formVisite = $this->createForm(VisiteType::class, $visite);
        
        $formVisite->handleRequest($request);
        if($formVisite->isSubmitted() && $formVisite->isValid()){
            $this->repository->add($visite);
            return $this->redirectToRoute('admin.voyages');
        }
        return $this->render("admin/admin.voyage.edit.html.twig", [
            'visite' => $visite,
            'formvisite'=> $formVisite->createView()
        ]);
    }
}
