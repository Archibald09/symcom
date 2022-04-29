<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
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

            $form = $this->createForm(ProductType::class, $product);

          

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
   public function adminProductEdit(Product $product2 ,Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response
   {
       

        $id = $product2->getId();


     
    

       $form = $this->createForm(ProductType::class, $product2);

       $form->handleRequest($request);

       $imageFile2 = $form->get('picture')->getData();
                        
                        

       if($form->isSubmitted()) {

                 /** @var UploadedFile $imageFile2 */

                            //Grace au form, nous pouvons récupérer les données insérer dans le champs 'picture'
            
                          
            
                            //On teste si l'on récupère bien une donnée
                        if($imageFile2){
            
                            //On stock le nom orignal du fichier (sans l'extension)
                            $originalName = pathinfo($imageFile2->getClientOriginalName(), PATHINFO_FILENAME);
            
                          //dump($originalName);
            
                          //On attribut un nom plus propre au ficher
                            $sluggedFileName2 =   $slugger->slug($originalName);
            
                            $newImageName2 = $sluggedFileName2 . '-' . uniqid() .'.'. $imageFile2->guessExtension();
                            // dump($this->getParameter('image_directory'));

                            try{

                                $imageFile2->move( $this->getParameter('image_directory'), $newImageName2 );
            
                            }catch(FileException $e){
                                return "Erreur: ".  $e->getMessage();
                            }
            
            
                            $product2->setPicture($newImageName2);
            
           }

           $manager->flush();

            $this->addFlash('info', "Le produit: " . $product2->getTitle() . " a bien été modifié ");

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
    public function AdminDeleteProducts(Product $product, EntityManagerInterface $manager) : Response
    {       
            

            $manager->remove($product);
            $manager->flush();

             $this->addFlash('danger', "Le produit: " . $product->getTitle() . " a bien été supprimé ");

        return $this->redirectToRoute('app_admin_products');
    }




}

