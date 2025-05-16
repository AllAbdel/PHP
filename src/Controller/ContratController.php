<?php

namespace App\Controller;

use App\Form\ContratTypeForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Contrat;
use App\Form\ContratType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\ContratRepository;

final class ContratController extends AbstractController
{

    #[Route('/contrat', name: 'contrat_index')]
    public function index(ContratRepository $repo): Response
    {
        return $this->render('contrat/index.html.twig', [
            'contrats' => $repo->findAll(),
        ]);
    }

    #[Route('/contrat/create', name: 'contrat_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $contrat = new Contrat();
        $contrat->setCreatedAt(new \DateTime());

        $form = $this->createForm(ContratTypeForm::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('file')->getData();

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                try {
                    $uploadedFile->move(
                        $this->getParameter('uploads_directory'), // à définir dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception("Erreur d'upload : " . $e->getMessage());
                }

                $contrat->setFile($newFilename);
            }

            $em->persist($contrat);
            $em->flush();

            return $this->redirectToRoute('contrat_index');
        }

        return $this->render('contrat/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/contrat/edit/{id}', name: 'contrat_edit')]
    public function edit(Request $request, Contrat $contrat, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ContratTypeForm::class, $contrat);
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

                $contrat->setFile($newFilename);
            }

            $em->flush();

            return $this->redirectToRoute('contrat_index');
        }

        return $this->render('contrat/edit.html.twig', [
            'form' => $form->createView(),
            'contrat' => $contrat,
        ]);
    }
    #[Route('/contrat/delete/{id}', name: 'contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete-contrat' . $contrat->getId(), $submittedToken)) {
            $em->remove($contrat);
            $em->flush();
        }

        return $this->redirectToRoute('contrat_index');
    }


}
