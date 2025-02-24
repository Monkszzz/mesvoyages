<?php

namespace App\Controller\admin;

use App\Entity\Visite;
use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminVoyagesController extends AbstractController {
    private VisiteRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(VisiteRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Route('/admin', name: 'admin.voyages')]
    public function index(): Response {
        $visites = $this->repository->findBy([], ['datecreation' => 'DESC']);
        
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    #[Route('/admin/suppr/{id}', name: 'admin.voyage.suppr', methods: ['POST'])]
    public function suppr(int $id): Response {
        $visite = $this->repository->find($id);

        if (!$visite) {
            $this->addFlash('error', 'Visite non trouvée.');
            return $this->redirectToRoute('admin.voyages');
        }

        $this->em->remove($visite);
        $this->em->flush();

        $this->addFlash('success', 'Visite supprimée avec succès.');
        return $this->redirectToRoute('admin.voyages');
    }
    
 #[Route('/admin/edit/{id}', name: 'admin.voyage.edit')]
public function edit(int $id, Request $request): Response {
    $visite = $this->repository->find($id);

    if (!$visite) {
        $this->addFlash('error', 'Visite non trouvée.');
        return $this->redirectToRoute('admin.voyages');
    }

    $formVisite = $this->createForm(VisiteType::class, $visite);
    $formVisite->handleRequest($request);

    if ($formVisite->isSubmitted() && $formVisite->isValid()) {
        $this->em->persist($visite);
        $this->em->flush();

        $this->addFlash('success', 'Visite modifiée avec succès.');
        return $this->redirectToRoute('admin.voyages');
    }

    return $this->render("admin/admin.voyage.edit.html.twig", [
        'formvisite' => $formVisite->createView(),
        'visite' => $visite // ✅ Correction : on passe bien la variable au template
    ]);
}
    
    #[Route('/admin/ajout', name: 'admin.voyage.ajout')]
    public function ajout(Request $request): Response {
        $visite = new Visite();
        $formVisite = $this->createForm(VisiteType::class, $visite);
        $formVisite->handleRequest($request);

        if ($formVisite->isSubmitted() && $formVisite->isValid()) {
            $this->em->persist($visite);
            $this->em->flush();

            $this->addFlash('success', 'Visite ajoutée avec succès.');
            return $this->redirectToRoute('admin.voyages');
        }

        return $this->render("admin/admin.voyage.ajout.html.twig", [
            'formvisite' => $formVisite->createView()
        ]);
    }
}
