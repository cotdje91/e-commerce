<?php

namespace App\DataFixtures;

use App\Entity\Categories;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    // Je créer un compteur pour les categuories de produits
    private $counter = 1;

        // Si je ne veux pas avoir a repeter le SLUGG dans mes fixtures j'utilies une fonction
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('Homme', null, $manager);

    $this->createCategory('Pantalons homme', $parent, $manager);
    $this->createCategory('Vestes homme', $parent, $manager);
    $this->createCategory('Parfum homme', $parent, $manager);

    $parent = $this->createCategory('Femme', null, $manager);

    $this->createCategory('Pantalons femme', $parent, $manager);
    $this->createCategory('Vestes femme', $parent, $manager);
    $this->createCategory('Parfums femme', $parent, $manager);

        // $parent = new Categories();
        // $parent->setName('Homme');
        // $parent->setSlug($this->slugger->slug($parent->getName())->lower());
        // $category->setParent($parent);
        // $manager->persist($parent);

        // $category = new Categories();
        // $category->setName('PantalonH1');
        // $category->setSlug($this->slugger->slug($category->getName())->lower());
        // $category->setParent($parent);
        // $manager->persist($category);

    $manager->flush();

    }

        // ON AUTOMATISE LA CREATION DE CATEGORIE
    public function createCategory(string $name, $parent = null, ObjectManager $manager)
    {

        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $manager->persist($category);

        // Je stocke le numero de chaque catégorie
        $this->addReference('cat-'.$this->counter,$category);

        // j'incremente mon counter stock ref
        $this->counter++;
        
        return $category; // Pour récuperer le parent 
    }
}


