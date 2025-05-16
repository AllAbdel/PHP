<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Form\FactureTypeForm;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'facture_index')]
    public function index(FactureRepository $repo): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $repo->findAll(),
        ]);
    }

    #[Route('/create', name: 'facture_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $facture = new Facture();

        $form = $this->createForm(FactureTypeForm::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('file')->getData();

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                try {
                    $uploadedFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception("Erreur lors de l’upload du fichier.");
                }

                $facture->setFile($newFilename);
            }

            $em->persist($facture);
            $em->flush();

            return $this->redirectToRoute('facture_index');
        }

        return $this->render('facture/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'facture_edit')]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FactureTypeForm::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('file')->getData();

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );

                $facture->setFile($newFilename);
            }

            $em->flush();

            return $this->redirectToRoute('facture_index');
        }

        return $this->render('facture/edit.html.twig', [
            'form' => $form->createView(),
            'facture' => $facture,
        ]);
    }

    #[Route('/delete/{id}', name: 'facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete-facture' . $facture->getId(), $request->request->get('_token'))) {
            $em->remove($facture);
            $em->flush();
        }

        return $this->redirectToRoute('facture_index');
    }
}
