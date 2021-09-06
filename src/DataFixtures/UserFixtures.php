<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Service\GenerateToken;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;
use Symfony\Component\Security\Core\User\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    private $generateToken;
    private $campusRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                GenerateToken $generateToken,
                                CampusRepository $campusRepository)
    {
        $this->passwordEncoder= $passwordEncoder;
        $this->generateToken=$generateToken;
        $this->campusRepository=$campusRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker= Faker\Factory::create('fr_FR');
        $allCampus= $this->campusRepository->findAll();
        $i=0;

       for($nbUsers=1;$nbUsers<=30;$nbUsers++){
           $user= new Participant();

           if($nbUsers===1){
               $user->addRoles('ROLE_ADMIN');
               $user->setAdministrateur(1);
               $user->setEmail('aurel@gmail.com');
           }else{
               $user->setEmail($faker->email);
               $user->setAdministrateur(0);
           }
           $user->setNom($faker->name);
           $user->setPrenom($faker->firstName);
           $user->setPassword('azerty12');
           $user->setUsername($faker->userName);
           $user->setActif(1);
           $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
           $user->setApiToken($this->generateToken->getToken($user));
           $user->setCampus($allCampus[$i]);
           if(count($allCampus)-1===$i)$i=0;
           else $i++;
            $manager->persist($user);

       }
       $manager->flush();

    }
}
