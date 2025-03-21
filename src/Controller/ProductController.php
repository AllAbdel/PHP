<?php

namespace App\Controller;

use App\Entity\Product; // Importation de l'entité Product
use Doctrine\ORM\EntityManagerInterface; // Importation de l'EntityManager
use App\Repository\ProductRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



final class ProductController extends AbstractController
{

    // ajout
    #[Route('/product/create/{name}/{price}/{stock}/{date}', name: 'product_create')]
    public function createProduct(string $name, float $price, int $stock, DateTime $date , EntityManagerInterface $em): Response
    {
        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setStock($stock);
        $product->setCreatedAt($date);

        $em->persist($product);
        $em->flush();

        return new Response("Produit ajouté : {$name} - {$price}€ - Stock : {$stock}");
    }
        // +10
        #[Route('/product/update_stock', name: 'product_update_stock')]
        public function updateStock(EntityManagerInterface $em, ProductRepository $repo): Response
        {
            $products = $repo->findAll();

            foreach ($products as $product) {
                $product->setStock($product->getStock() + 10);
            }

            $em->flush();
            return new Response("Stock mis à jour pour tous les produits.");

        }


        // Liste
        #[Route('/products', name: 'product_list')]
        public function listProducts(ProductRepository $repo): Response
        {
            $products = $repo->findAll();
            return $this->render('product/list.html.twig', ['products' => $products]);
        }


        // tri par nom
        #[Route('/product/{name}', name: 'product_by_name')]
        public function getProductByName(string $name, ProductRepository $repo): Response
        {
            $products = $repo->findBy(['name' => $name]);
            return $this->render('product/list.html.twig', ['products' => $products]);
        }


        // commander par id (reduire de 1 le stock en gros)
        #[Route('/product/order/{id}', name: 'product_order')]
        public function orderProduct(int $id, EntityManagerInterface $em, ProductRepository $PRepo): Response
        {
            $product = $PRepo->find($id);

            if ($product && $product->getStock() > 0) {
                $product->setStock($product->getStock() - 1);
                $em->flush();
                return $this->render('product/order.html.twig', ['product' => $product]);
            }
        }



        // del
        #[Route('/product/delete/{id}', name: 'product_delete')]
        
        public function deleteProduct(Product $product, EntityManagerInterface $em){
            $em->remove($product);
            return $this->render('product/del.html.twig', ['product' => $product]);

        }
        


}

