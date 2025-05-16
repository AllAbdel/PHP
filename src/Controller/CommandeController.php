<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Form\CommandeTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandeController extends AbstractController
{

#[Route('/commande', name: 'commande_index')]
public function index(CommandeRepository $repo): Response
{
    $commandes = $repo->findAll();

    return $this->render('commande/index.html.twig', [
        'commandes' => $commandes,
    ]);
}


    #[Route('/commande/create', name: 'commande_create')]
    public function create(Request $request, EntityManagerInterface $em): Response {
    $commande = new Commande();
    $form = $this->createForm(CommandeTypeForm::class, $commande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $commande->setCreatedAt(new \DateTimeImmutable());
        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('commande_index');

    }

    return $this->render('commande/create.html.twig', [
        'form' => $form->createView()
    ]);
}

}
