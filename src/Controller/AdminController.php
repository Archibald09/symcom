<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/products', name :'app_admin_create')]
    public function adminProduct(ProductRepository $productRepository) : Response 
    {

        $em = $this->getDoctrine()->getManager();

        $colonnes = $em->getClassMetadata(Product::class)->getFieldNames();

        $products = $productRepository->findAll();

        return $this->render('admin/products.html.twig', [
                'colonnes' => $colonnes,
                'products' => $products
        ]);
    }
}
