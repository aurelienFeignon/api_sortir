<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Ville;

class AppFixtures extends Fixture
{
    private $villeRepo;

    public function __construct(VilleRepository $villeRepo)
    {
        $this->villeRepo= $villeRepo;
    }


    public function load(ObjectManager $manager)
    {
        $villes=[
          1=>[
            'nom'=>'Tours',
            'codePostal'=>'37000'
          ],
            2=>[
                'nom'=>'Nantes',
                'codePostal'=>'44000'
            ],
            3=>[
                'nom'=>'Niort',
                'codePostal'=>'79000'
            ],
            4=>[
                'nom'=>'Rennes',
                'codePostal'=>'35000'
            ],
        ];

        foreach ($villes as $value){
            $ville= new Ville();
            $ville->setNom($value['nom']);
            $ville->setCodePostal($value['codePostal']);
            $manager->persist($ville);
        }
        $manager->flush();

        $lieux=[
          1=>[
            'nom'=>'Cinéma CGR Tours Centre',
            'rue'=>'4 Place François Truffaut',
            'latitude'=>'47.38809',
            'longitude'=>'0.6932648',
            'ville'=>'Tours'
          ],
            2=>[
                'nom'=>'Rose Bonbon',
                'rue'=>'104 Rue du Commerce',
                'latitude'=>'47.3946674',
                'longitude'=>'0.6825767',
                'ville'=>'Tours'
            ],
            3=>[
                'nom'=>'l\'excalibur',
                'rue'=>'35 Rue Briçonnet',
                'latitude'=>'47.3945539',
                'longitude'=>'0.6813991',
                'ville'=>'Tours'
            ],
            4=>[
                'nom'=>'BowlCenter Tours',
                'rue'=>'28 Av. Marcel Mérieux',
                'latitude'=>'47.3651529',
                'longitude'=>'0.6794991',
                'ville'=>'Tours'
            ],
        ];

        foreach ($lieux as $value){
            $lieu= new Lieu($value['nom'],
                            $value['rue'],
                            $value['latitude'],
                            $value['longitude']);
            $lieu->setVille($this->villeRepo->findOneBy(['nom'=>$value['ville']]));
            $manager->persist($lieu);

        }
        $manager->flush();
    }
}
