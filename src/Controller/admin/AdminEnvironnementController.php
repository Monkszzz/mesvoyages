<?php

namespace App\Controller\admin;

use App\Entity\Environnement;
use App\Repository\EnvironnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEnvironnementController extends AbstractController {
    private EnvironnementRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(EnvironnementRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Route('/admin/environnements', name: 'admin.environnements')]
    public function index(): Response {
        $environnements = $this->repository->findAll();

        return $this->render("admin/admin.environnements.html.twig", [
            'environnements' => $environnements
        ]);
    }

    #[Route('/admin/environnement/suppr/{id}', name: 'admin.environnement.suppr')]
    public function suppr(int $id): Response {
        $environnement = $this->repository->find($id);

        if (!$environnement) {
            $this->addFlash('error', 'Environnement non trouvé.');
            return $this->redirectToRoute('admin.environnements');
        }

        $this->em->remove($environnement);
        $this->em->flush();

        $this->addFlash('success', 'Environnement supprimé avec succès.');
        return $this->redirectToRoute('admin.environnements');
    }

    #[Route('/admin/environnement/ajout', name: 'admin.environnement.ajout', methods: ['POST'])]
    public function ajout(Request $request): Response {
        $nom = $request->request->get('nom');

        if (!$nom) {
            $this->addFlash('error', 'Le nom de l\'environnement est requis.');
            return $this->redirectToRoute('admin.environnements');
        }

        $environnement = new Environnement();
        $environnement->setNom($nom);

        $this->em->persist($environnement);
        $this->em->flush();

        $this->addFlash('success', 'Environnement ajouté avec succès.');
        return $this->redirectToRoute('admin.environnements');
    }
}
