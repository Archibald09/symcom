<?php

namespace App\Cart;

use App\Cart\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

//On se créer notre propre classe pour la gestion du panier "CartServiceé"
class CartService {

    //Sachant que l'on aura besoin d'utiliser la session et connaitres tous les produits disponibles en bdd, on instancie ces 2 classes dans le constructeur et on se les injecte en tant que  propriétés de notre classe
        protected $session;
        protected $productRepo;


        public function __construct(SessionInterface $session, ProductRepository $productRepo) {
            $this->session = $session;
            $this->productRepo = $productRepo;
        }

        //On créer on fonction add avec tous le traitement nécéssaire afin de ne pas surcharger le controller "CartController"
        public function add(int $id) //On demande à récupéré l'id d'un produit
        {
             $cart = $this->session->get('cart', []); //ici on va chercher si on a un tableau 'cart' dans la session sinon on créer un talbeau vide et on stocke le résultat dans $cart 

             if(array_key_exists($id, $cart)) //On teste si le produit ($id) existe dans ce panier
             {
                 $cart[$id]++;// si c'est le cas on l'incrémente
             }
             else
             {
                 $cart[$id] = 1; //Sinon on l'ajoute au tableau ($cart)
             }

             $this->session->set('cart', $cart); //On sauvegarde les nouvelles données du tableau $cart.
        }


      
        public function remove(int $id)
       {
           $cart = $this->session->get('cart', []);

           unset($cart[$id]);

           $cart = $this->session->set('cart', $cart);
       }


       public function decrement(int $id)
       {
           $cart = $this->session->get('cart', []);

           if(!array_key_exists($id, $cart))
           {
               return;
           }

           if($cart[$id] === 1)
           {
               $this->remove($id);
               return;
           }
           else
           {
               $cart[$id]--;
               
               $cart = $this->session->set('cart', $cart);
           }
       }




       public function getTotal(): int
       {
           $total = 0;

           foreach($this->session->get('cart', []) as $id => $qte)
           {
                $product = $this->productRepo->find($id);

                if(!$product)
                {
                    continue;
                }

                $total += ($product->getPrice() * $qte);
           }

           return $total;
       }



       
        public function getDetail() : array
        {
            $panier = [];

            foreach($this->session->get('cart', []) as $id => $qte)
            {
                $product = $this->productRepo->find($id);

                if(!$product)
                {
                    continue;
                }
                
                // $panier[] = [
                //     'product' => $product,
                //     'qte' => $qte
                // ];

                 $panier[] = new CartItem($product, $qte);
            }

            return $panier;
        }

}