<?php

namespace App\DataFixtures;

use App\Entity\Biens;
use App\Entity\Villes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        à utiliser pour remplir avec faker
        ini_set('memory_limit', '-1');
        $listes_villes = array('Ain Zaghouan',
            'Amilcar',
            'Ariana',
            'Ben Arous',
            'Bizerte',
            'Carthage',
            'Centre Commercial Ikram',
            'Chotrana 1',
            'Chotrana 2',
            'Cité Aziza',
            'Cité El Hana',
            'Cité El Mahrajene',
            'Cité El Wafa',
            'Cité Erriadh',
            'Cité Olympeade',
            'Dar Djerba',
            'Gammarth',
            'Grombalia',
            'Hammamet',
            'Jardins de Carthage',
            'Kheireddine',
            'Korba',
            'Ksar Said',
            'La Goulette',
            'La Goulette Port',
            'La Marsa',
            'La Soukra',
            'Le Kram',
            'Les Berges du Lac',
            'Les Berges du Lac 2',
            'Megrine',
            'Nouvelle Ariana',
            'Raoued',
            'Saint Gobin',
            'Salambo',
            'Sidi Bou Saïd',
            'Sidi Daoud',
            'Sidi Hassine',
            'Sidi Rezig',
            'Station Marchandises Fret',
            'Tunis',
            'Zaghouan');

        $faker = Faker\Factory::create('fr_FR');
        foreach ($listes_villes as $listes_ville){
            $ville = new Villes();
            $ville->setNom($listes_ville);
            $ville->setPays('Tunisie');
            $manager->persist($ville);
        }
        $manager->flush();

        $villes = $manager->getRepository(Villes::class)->findAll();
        for ($i= 0; $i<30000; $i++){
            $biens = new Biens();
            $biens->setNom($faker->words(5,true));
            $biens->setDescription($faker->paragraph);
            $biens->setAscenceur($faker->boolean());
            $biens->setChauffage($faker->boolean());
            $biens->setCideosurveillance($faker->boolean());
            $biens->setConcierge($faker->boolean());
            $biens->setEclairageexterieur($faker->boolean());
            $biens->setGardien($faker->boolean());
            $biens->setRef($faker->numberBetween(10000,15000));
            $biens->setMaisongardien($faker->boolean());
            $biens->setPieces($faker->numberBetween(1,5));
            $biens->setSurface($faker->numberBetween(20,700));
            $biens->setEtat('bon état');
            $biens->setEtage($faker->numberBetween(0,8));
            $biens->setClimatisation($faker->boolean());
            $biens->setType($faker->boolean());
            $biens->setNeuf($faker->boolean());
            $biens->setVille($faker->randomElement($villes));
            $biens->setTypeBien($faker->randomElement(['Appartement','Maison','Terrain','Commerce','Garage/Parking','Immeuble','Bureau','Cave']));
            $biens->setPrix($faker->randomFloat(2,400,10000));
            $manager->persist($biens);
        }

        $manager->flush();
    }
}
