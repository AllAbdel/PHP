<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClientController extends AbstractController
{

#[Route('/client', name: 'client_index')]
public function index(ClientRepository $repo): Response
{
    $clients = $repo->findAll();

    return $this->render('client/index.html.twig', [
        'clients' => $clients,
    ]);
}

    #[Route('/client/create', name: 'client_create')]
    public function create(Request $request, EntityManagerInterface $em): Response {
        $client = new Client();
        $form = $this->createFormBuilder($client)
            ->add('name')
            ->add('email')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/create.html.twig', [
            'form' => $form->createView()
        ]);
}

}
