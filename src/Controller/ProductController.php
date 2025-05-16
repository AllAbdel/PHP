<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;


final class ProductController extends AbstractController
{

#[Route('/product', name: 'product_index')]
public function index(ProductRepository $repo): Response
{
    $products = $repo->findAll();

    return $this->render('product/index.html.twig', [
        'products' => $products,
    ]);
}

    #[Route('/product/create', name: 'product_create')]
    public function create(Request $request, EntityManagerInterface $em): Response {
    $product = new Product();
    $form = $this->createForm(ProductTypeForm::class, $product); // ✅ ici on utilise bien le formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($product);
        $em->flush();
        return $this->redirectToRoute('product_index');
    }

    return $this->render('product/create.html.twig', [
        'form' => $form->createView()
    ]);
}


}
