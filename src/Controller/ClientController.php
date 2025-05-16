<?php

namespace App\Controller;

use App\Form\ClientTypeForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ClientController extends AbstractController
{
    #[Route('/client/create', name: 'client_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $client = new Client();

        $form = $this->createForm(ClientTypeForm::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Optionnel : ajouter index() pour lister les clients
    #[Route('/client', name: 'client_index')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig');
    }
}
