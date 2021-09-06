<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $campus=[
            1=>[
              'nom'=>'Nantes'
            ],
            2=>[
                'nom'=>'Niort'
            ],
            3=>[
                'nom'=>'Rennes'
            ],
        ];

        foreach ($campus as $key=>$value){
            $newCampus= new Campus();
            $newCampus->setNom($value['nom']);
            $manager->persist($newCampus);
        }

        $manager->flush();
    }
}
