<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $etats= [
            1=>[
              'libelle'=>'Créée'
            ],
            2=>[
                'libelle'=>'Cloturée'
            ],
            3=>[
                'libelle'=>'Publié'
            ],
        ];

        foreach ($etats as $value){
            $etat= new Etat();
            $etat->setLibelle($value['libelle']);
            $manager->persist($etat);
        }

        $manager->flush();
    }
}
