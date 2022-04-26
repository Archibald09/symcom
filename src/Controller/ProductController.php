<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
    * Pour sélectionner des données dans une table SQL, nous avons besoin d'un Repository de     l'entitcorespondante Product => 'ProductRepository'. Un Repository est une classe générée par doctrine, ellnous permet de faire des selections (SELECT) en base de données. Pour ce la elle a à dispositiodifférentes méthodes (find, findByOne, findAll, etc)
    */

     
    #[Route("/product", name : "app_product")]
     

    public function index(ProductRepository $products): Response
    {

        // $products = $this->getDoctrine()->getRepository(Product::class);
        
        $product = $products->findAll();

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route("/product/{id}", name : "app_show", requirements: ["id" => "\d+"], methods : ["GET", "POST"])]

    public function show(Product $product) : Response {
        //$id

        //$productRepo = $this->getDoctrine()->getRepository(Product::class);
        
        //$product = $productRepo->fin($id);

        /* Tout ça grâce au ParamConverter de Symfony, en gros il voit que l’on a besoin d’un article et aussi 
        d’un ID, il va donc chercher l’article avec l’identifiant et l’envoyer à la fonction show().
        L' @ParamConverterannotation appelle des convertisseurs pour convertir les paramètres de 
        demande en objets. Ces objets sont stockés en tant qu'attributs de requête et peuvent donc être 
        injectés en tant qu'arguments de méthode de contrôleur:
        Symfony comprend qu’il y a un article a passé et que dans la route il y a un ID, il va donc chercher le 
        bon article avec le bon identifiant, cela pourrait très bien marcher avec le titre, le nom etc…
        Nous avons donc des fonctions beaucoup plus courte. */


        return $this->render('/product/show.html.twig', [
            'product' => $product
        ]);
    }

}
