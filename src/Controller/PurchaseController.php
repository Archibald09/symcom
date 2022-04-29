<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    public function purchaseConfirm(): Response
    {
        //0 si la personne n'est pas connecté, rediriger

        //1 Un formulaire de commande (make:form)

        //2 Lire le formulaire et le remplir avec les informations de la commande(form->handleRequest)

        //3 Vérifier si le formulaire est valide? soumis ou non ? (isSubmitted &&isValid())

        //4 S'il n'y a rien dans le panier, on redirige(SessionInterface / CartService)

        //5 Créer la commande (instance de Purchase $purchase)

        //6 Relier la commande avec l'utilisateur en cours pour
        
        //7 Lier le panier et la commande (Session ou CartService), instance de purchasedetail
        
        //8 Enregistré la commande en bdd (EntityManagerInterface)

        return $this->render('purchase/confirm.html.twig');
    }
}
