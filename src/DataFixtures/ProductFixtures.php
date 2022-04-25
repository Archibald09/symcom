<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{

    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
            $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        //Nous instancions un objet $faker, du composant FAKERPHP\FAKER;
        // $faker nous permettra de générer des fausses données plus réalistes. => se référer à la doc:
        https://fakerphp.github.io/;
 
        $faker = \Faker\Factory::create('fr_FR');

        for($j = 0; $j <= 4; $j++) {
            
            //A chaque tour de boucle nous créons une nouvelle catégorie (4 au total)
            $category = new Category();

            $category->setName($faker->sentence())
                     ->setSlug($this->slugger->slug(strtoupper($category->getName())));
                    

                     $manager->persist($category);
        
            for($i = 0; $i <= mt_rand(3, 20); $i++) {

                        //A chaque tour de boucle on créer une nouvelle instance de notre entity Product (un nouveau produit)
                        $product = new Product(); 
                        //On insère dans chaque champ de la table product (en bdd) grâce aux setteurs.
                        $product->setTitle($faker->sentence())
                        ->setContent($faker->realText($faker->numberBetween(10, 20)))
                        ->setPrice(mt_rand(15, 35))// mt_rand (fonction php qui permet de générer un nb au hasard, en fonction des paramètres qu'on lui a donné (15, 35))
                        ->setPicture("https://picsum.photos/id/" . mt_rand(10, 100) . "/800/500")
                        ->setCategory($category);
                   
                        $manager->persist($product);// On fait persister nos données (on les sauvegarde en tampon);
            }

        

                $manager->flush(); //Ici, on les enregistres en base de données 
        }
    }
    
}
