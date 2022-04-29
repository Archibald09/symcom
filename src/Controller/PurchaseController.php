<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Form\PurchaseType;
use App\Entity\Purchasedetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseController extends AbstractController
{
    #[Route('/purchase', name: 'app_purchase')]
    public function index(): Response
    {
        $user = $this->getUser();

        if(!$user) {
            $this->redirectToRoute('app_login');
        }

        return $this->render('purchase/index.html.twig', [
            
        ]);
    }

    #[Route('/purchase/confirm', name : 'purchase_confirm')]

    public function purchaseConfirm(Request $request, CartService $cartService, EntityManagerInterface $manager): Response
    {
        //0 si la personne n'est pas connecté, rediriger
        
        $user = $this->getUser();
        if(!$user)
        {
            $this->addFlash("warning", "Accès non autorisé, vous n'êtes pas authentifié");
            return $this->redirectToRoute('cart_show');
        }

        //1 Un formulaire de commande (make:form)

        $form = $this->createForm(PurchaseType::class);

        dd($form->getData());



        //2 Lire le formulaire et le remplir avec les informations de la commande(form->handleRequest)
        
        $form->handleRequest($request);

        //3 Vérifier si le formulaire est valide? soumis ou non ? (isSubmitted &&isValid())

            if(!$form->isSubmitted())
            {
                $this->addFlash('warning', "Vous devez remplir le formulaire pour valider la commande");

                return $this->redirectToRoute('cart_show');
            }

        //4 S'il n'y a rien dans le panier, on redirige(SessionInterface / CartService)

        $cartItems = $cartService->getDetail();
        if(count($cartItems) == 0)
        {
            $this->addFlash('warning', "Votre panier est vide");

            return $this->redirectToRoute('app_product');
        }

        if($form->get('FullName')->getData() == null || $form->get('adress')->getData() == null || $form->get('postalCode')->getData() == null || $form->get('city')->getData() == null )
        {
            $this->addFlash('warning', "Merci de remplir tous les champs afin de finaliser votre commande");
            return $this->redirectToRoute('app_show');
        }
        //5 Créer la commande (instance de Purchase $purchase)

        /**@var Purchase */
        $purchase = $form->getData();

        //6 Relier la commande avec l'utilisateur en cours 
        $purchase->setUser($user)
                 ->setPurchaseAt(new \DateTime)
                 ->setTotal($cartService->getTotal());
        
        $manager->persist($purchase);
        
        //7 Lier le panier et la commande (Session ou CartService), instance de purchasedetail

        foreach($cartService->getDetail() as $item) 
        {
            $purchaseDetail = new Purchasedetail;

            $purchaseDetail->setPurchase($purchase)
                           ->setProduct($item->product)
                           ->setProductName($item->product->getTitle())
                           ->setQuantity($item->qte)
                           ->setTotal($item->getTotal())
                           ->setProductPrice($item->product->getPrice());

                           $manager->persist($purchase);
        }
        
        //8 Enregistré la commande en bdd (EntityManagerInterface)

        $manager->flush();
        $this->addFlash("success", "La commande a bien été enregistré");

        return $this->render('purchase/confirm.html.twig');
    }

    #[Route('/purchase/orders', name: 'app_purchase_orders')]
    public function orderList(): Response
    {   
        /**@var User */
        $user = $this->getUser();

        if(!$user) 
        {
            $this->redirectToRoute('app_login');
        }

        return $this->render('purchase/orders.html.twig', [
           'purchases' => $user->getPurchases()
        ]);
    }

}
