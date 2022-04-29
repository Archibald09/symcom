<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\PurchaseType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, ProductRepository $productRepo, CartService $cartService, Request $request): Response
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

            if($request->query->get('stay'))
            {
                return $this->redirectToroute('cart_show');
            }
            

          return $this->redirectToRoute('app_show', [
                'id' => $id
            ]);

    }


    /**
     * @Route("/cart", name="cart_show")
     */
    public function showCart(CartService $cartService, SessionInterface $session) : Response
    {

        $form = $this->createForm(PurchaseType::class);


        $panier = $cartService->getDetail();

        $total = $cartService->getTotal();

       
        
        return $this->render("cart/index.html.twig", [
            'panier' => $panier,
            'total' => $total,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     */
      public function deleteProduct($id, ProductRepository $productRepo, CartService $cartService) : Response
      {
           $product = $productRepo->find($id);
           
           if(!$product)
           {
               throw $this->createNotFoundException('Cet article n\'existe pas');
           }

           $cartService->remove($product->getId());

           $this->addFlash("success", "Le produit " . $product->getId() . " a bien été supprimé");

          return $this->redirectToroute('cart_show');
      }  


      /**
       * @Route("/cart/remove-one/{id}", name="cart_remove_one", requirements= {"id": "\d+"})
       */
        public function decrement($id, ProductRepository $productRepo,CartService $cartService) : Response
        {

            $product = $productRepo->find($id);
           
            if(!$product)
            {
                throw $this->createNotFoundException('Cet article est en quantité limité');
            }
 
            $cartService->decrement($product->getId());
 
            $this->addFlash("success", "Le produit " . $product->getId() . " a bien été retiré");
 

            return $this->redirectToroute('cart_show');
        }
}


