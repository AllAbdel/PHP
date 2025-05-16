<?php

namespace App\Controller;

use App\Form\ClientTypeForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;



final class ClientController extends AbstractController
{

    #[Route('/client', name: 'client_index')]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }
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
   #[Route('/client/edit/{id}', name: 'client_edit')]
    public function edit(int $id, Request $request, ClientRepository $repo, EntityManagerInterface $em): Response
    {
        $client = $repo->find($id);

        if (!$client) {
            throw $this->createNotFoundException('Client introuvable.');
        }

        $form = $this->createForm(ClientTypeForm::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ]);
    }


    #[Route('/client/delete/{id}', name: 'client_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, ClientRepository $repo, EntityManagerInterface $em): RedirectResponse
    {
        $client = $repo->find($id);

        if (!$client) {
            throw $this->createNotFoundException('Client introuvable.');
        }

        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete-client' . $client->getId(), $submittedToken)) {
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('client_index');
    }

}
