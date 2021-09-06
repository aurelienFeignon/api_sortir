<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Ville;

class UserSortieFixtures extends Fixture
{
    private $lieuRepo;
    private $etatRepo;
    private $participantRepo;

    public function __construct(LieuRepository  $lieuRepo,
                                EtatRepository $etatRepo,
                                ParticipantRepository $participantRepository)
    {
        $this->lieuRepo= $lieuRepo;
        $this->etatRepo=$etatRepo;
        $this->participantRepo=$participantRepository;
    }


    public function load(ObjectManager $manager)
    {
        $faker= Faker\Factory::create('fr_FR');
        $sorties=[
          1=>[
            'nom'=>'Les Méchants au cinema',
            'dateHeureDebut'=>$faker->dateTimeBetween('now','1years'),
            'duree'=>120,
            'nbInscriptionMax'=>200,
            'infosSortie'=>'On se donne rendez vous au CGR pour aller voir les méchants',
             'dateLimiteInscriptions'=>$faker->dateTimeBetween('now','1years')
          ],
            2=>[
                'nom'=>'Petite pinte au Rose Bonbon',
                'dateHeureDebut'=>$faker->dateTimeBetween('now','1years'),
                'duree'=>120,
                'nbInscriptionMax'=>8,
                'infosSortie'=>'Profitons du beau temps pour boire un petit verre en térasse',
                'dateLimiteInscriptions'=>$faker->dateTimeBetween('now','1years')
            ],
            3=>[
                'nom'=>'Soirée d\'anniversaire à l\'excalibur',
                'dateHeureDebut'=>$faker->dateTimeBetween('now','1years'),
                'duree'=>300,
                'nbInscriptionMax'=>50,
                'infosSortie'=>'Viens feter ton anniversaire avec moi',
                'dateLimiteInscriptions'=>$faker->dateTimeBetween('now','1years')
            ],
            4=>[
                'nom'=>'Bowling entre amis à BowlCenter Tours',
                'dateHeureDebut'=>$faker->dateTimeBetween('now','1years'),
                'duree'=>120,
                'nbInscriptionMax'=>10,
                'infosSortie'=>'un bowling pour se detendre un peu',
                'dateLimiteInscriptions'=>$faker->dateTimeBetween('now','1years')
            ],
        ];

        $lieux= $this->lieuRepo->findAll();
        $participants = $this->participantRepo->findAll();
        $i=0;
        foreach ($sorties as $value){
            $sortie= new Sortie();
            $sortie->setNom($value['nom']);
            $sortie->setEtat($this->etatRepo->findOneBy(['libelle'=>'Créée']));
            $sortie->setDateHeureDebut($value['dateHeureDebut']);
            $sortie->setDuree($value['duree']);
            $sortie->setNbInscriptionMax($value['nbInscriptionMax']);
            $sortie->setInfosSortie($value['infosSortie']);
            $sortie->setDateLimiteInscriptions($value['dateLimiteInscriptions']);
            $sortie->setLieu($lieux[$i]);
            $i++;
            $sortie->setOrganisateur($participants[rand(0,count($participants)-1)]);
            $sortie->setCampus($sortie->getOrganisateur()->getCampus());

            $manager->persist($sortie);

        }
        $manager->flush();
    }
}
