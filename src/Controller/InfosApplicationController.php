<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\InfosApplication;
use App\Form\InfosApplicationTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\InfosApplicationRepository;
use App\Form\InfosApplicationType;

final class InfosApplicationController extends AbstractController
{
    #[Route('/application/create', name: 'application_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $application = new InfosApplication();
        $application->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(InfosApplicationTypeForm::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($application);
            $em->flush();

            return $this->redirectToRoute('application_create');
        }

        return $this->render('infos_application/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/application/edit/{id}', name: 'application_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, InfosApplicationRepository $repo): Response
    {
        $application = $repo->find($id);

        if (!$application) {
            throw $this->createNotFoundException('Application introuvable.');
        }

        $form = $this->createForm(InfosApplicationTypeForm::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('application_edit', ['id' => $application->getId()]);
        }

        return $this->render('infos_application/edit.html.twig', [
            'form' => $form->createView(),
            'application' => $application,
        ]);
    }
}