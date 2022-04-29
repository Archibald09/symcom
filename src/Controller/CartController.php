<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, ProductRepository $productRepo, CartService $cartService): Response
    {
       
        //1 est-ce le produit existe en bdd?

        //2 Pouvoir retrouve le panier sous forme de tableau dans la $_SESSION

        //3 Voir si le produit via son ($id) existe réellement dans le panier?

        //4 Si c'est le cas on l'incrément ($produit++)

        // if(array_key_exists($id, $panier))
        //{
                // $panier[$id]++;
        //}
        // else
        // {
                // $panier[$id] = +1;
        // }

        //5 Sinon on l'ajoute au panier

        //6 Enregistrer le tableau (panier) et le mettre à jour
        //$request->getSession()->set(panier, $panier);

            $product = $productRepo->find($id);

            if(!$product)
            {
                throw new NotFoundHttpException('Le produit' . $id . "n'existe pas en bdd");  
            }

            $cartService->add($id);

            $this->addFlash('success', "Produit ajouté");

          return $this->redirectToRoute('app_show', [
                'id' => $id
            ]);

    }


    /**
     * @Route("/cart", name="cart_show")
     */
    public function showCart(CartService $cartService) : Response
    {
         
        $panier = $cartService->getDetail();

        $total = $cartService->getTotal();
        
        return $this->render("cart/index.html.twig", [
            'panier' => $panier,
            'total' => $total
        ]);
    }
}
