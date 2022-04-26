<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/register', name: 'app_register')]
    
    public function register(Request $request, UserPasswordEncoderInterface $hasher): Response
    {   

        $user = new User();

        //On exécute la method createFom (de la classe AbstractController), afin de créer un formulaire en rapport avec la classe 'RegistrationType' pour utiliser les getters et setters afin de remplir l'objet $user.


        $form = $this->createForm(RegistrationType::class, $user, [
            'validation_groups' => ['registration', 'default']
        ]);

        

        //Nous définissons un groupe de validation de contraintes afin qu'elle ne soient prise en compte uniquement lors de l'inscription et non lors de la modification dans le backOffice.

        $form->handleRequest($request);

        dump($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $hash = $hasher->encodePassword($user, $user->getPassword());

            dump($hash);
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
