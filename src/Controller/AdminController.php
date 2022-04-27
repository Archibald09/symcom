<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\ProductsType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @Route("/admin/products", name="app_admin_products")
     */
    public function adminProducts(ProductRepository $productRepository) : Response
    {

        $em = $this->getDoctrine()->getManager();

        $colonnes = $em->getClassMetadata(Product::class)->getFieldNames();

        $products = $productRepository->findAll();

        return $this->render('admin/products.html.twig', [
           'colonnes' => $colonnes,
           'products' => $products     
        ]);
    }


    /**
     * @Route("/admin/products/create", name="admin_products_create")
     * 
     */
    public function adminProductCreate(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response
    {
       
            $product = new Product();
           

           $id = $product->getId();

        $form = $this->createForm(ProductsType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            //Class Symfony permettant de traiter les fichiers (type file)
            /** @var UploadedFile $imageFile */

                        //Grace au form, nous pouvons récupérer les données insérer dans le champs 'picture'
          
                $imageFile = $form->get('picture')->getData();
                     

              

            // dump($imageFile->guessExtension()); fonction permettant l'extension d'un fichier

            //On teste si l'on récupère bien une donnée
            if($imageFile){

                //On stock le nom orignal du fichier (sans l'extension)
                $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

              //dump($originalName);

              //On attribut un nom plus propre au ficher
                $sluggedFileName =   $slugger->slug($originalName);

                $newImageName = $sluggedFileName . '-' . uniqid() .'.'. $imageFile->guessExtension();
                // dump($this->getParameter('image_directory'));

                //On va tenter de copier l'image dans le bon dossier
                try{

                    $imageFile->move( $this->getParameter('image_directory'), $newImageName );

                }catch(FileException $e){
                    return "Erreur: ".  $e->getMessage();
                }


                $product->setPicture($newImageName);

            }

           


            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', "Le produit: " . $product->getTitle() . " a bien été ajouté ");

             return $this->redirectToRoute('app_admin_products');
        }


        return $this->render('admin/products/create.html.twig', [
               'form' => $form->createView(),
               'id' => $id
        ]);
    }

    /**
    * @Route("/admin/products/edit/{id}", name="admin_products_edit")
    */
   public function adminProductEdit(Product $product ,Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response
   {
       

        $id = $product->getId();

    

       $form = $this->createForm(ProductsType::class, $product);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {

           //Class Symfony permettant de traiter les fichiers (type file)
           /** @var UploadedFile $imageFile */

                       //Grace au form, nous pouvons récupérer les données insérer dans le champs 'picture'
         
               $imageFile = $form->get('picture')->getData();
                    

             

           // dump($imageFile->guessExtension()); fonction permettant l'extension d'un fichier

           //On teste si l'on récupère bien une donnée
           if($imageFile){

               //On stock le nom orignal du fichier (sans l'extension)
               $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

             //dump($originalName);

             //On attribut un nom plus propre au ficher
               $sluggedFileName =   $slugger->slug($originalName);

               $newImageName = $sluggedFileName . '-' . uniqid() .'.'. $imageFile->guessExtension();
               // dump($this->getParameter('image_directory'));

               //On va tenter de copier l'image dans le bon dossier
               try{

                   $imageFile->move( $this->getParameter('image_directory'), $newImageName );

               }catch(FileException $e){
                   return "Erreur: ".  $e->getMessage();
               }

               $product->setPicture($newImageName);
           }

           
  


           $manager->persist($product);
           $manager->flush();

           $this->addFlash('success', "Le produit: " . $product->getTitle() . " a bien été ajouté ");

           return $this->redirectToRoute('app_admin_products');
       }


       return $this->render('admin/products/edit.html.twig', [
              'form' => $form->createView(),
              'id' => $id
       ]);
   }

    /**
    * @Route("/admin/products/delete/{id}", name="admin_products_delete")
    */

    public function adminDeleteProducts(Product $product, EntityManagerInterface $manager, $id) : Response
    {
        $id = $product->getId();
        
        
        $manager->persist($product);
        $manager->flush();


        $this->addFlash('success', "Le produit: " . $product->getTitle() . " a bien été ajouté ");

        return $this->redirectToRoute('app_admin_products');


     }

    
}
