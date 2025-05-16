<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InfosApplicationController extends AbstractController
{
    #[Route('/infos/application', name: 'app_infos_application')]
    public function index(): Response
    {
        return $this->render('infos_application/index.html.twig', [
            'controller_name' => 'InfosApplicationController',
        ]);
    }
}
