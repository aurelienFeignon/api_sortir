<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SortieController extends AbstractController
{
    /**
     * @Route("/api/sortie", name="getSortie", methods={"GET"})
     */
    public function getSortie(SortieRepository $sortieRepository): Response
    {
       return $this->json($sortieRepository->findAll(),200, [], ['groups'=>'sortie:read']);
    }

    /**
     * @Route("/api/sortie", name="postSortie", methods={"POST"})
     */
    public function postSortie  (Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer,
                                ValidatorInterface $validator, ParticipantRepository $participantRepository,
                                CampusRepository $campusRepository, VilleRepository $villeRepository, LieuRepository $lieuRepository,
                                EtatRepository $etatRepository): Response
    {
        $jsonRecu= $request->getContent();
        $sortie= $serializer->deserialize($jsonRecu, Sortie::class, 'json');
        $idOrganisateur= json_decode($jsonRecu)->idOrganisateur;
        $organisateur= $participantRepository->find($idOrganisateur);
        if(is_null($organisateur)){
            return $this->json(['error'=>"L'organisateur n'existe pas"],400);
        }
        $idCampus= json_decode($jsonRecu)->idCampus;
        $campus= $campusRepository->find($idCampus);
        if(is_null($campus)){
            return $this->json(['error'=>"Le campus n'existe pas"],400);
        }
        $idVille= json_decode($jsonRecu)->idVille;
        $ville= $villeRepository->find($idVille);
        if(is_null($ville)){
            return $this->json(['error'=>"La ville n'existe pas"],400);
        }
        if(isset(json_decode($jsonRecu)->idLieu)) {
            $idLieu = json_decode($jsonRecu)->idLieu;
            $lieu = $lieuRepository->find($idLieu);
        }else{
            $nomLieu=json_decode($jsonRecu)->nomLieu;
            $rueLieu=json_decode($jsonRecu)->rueLieu;
            $latitudeLieu=json_decode($jsonRecu)->latitudeLieu;
            $longitudeLieu=json_decode($jsonRecu)->longitudeLieu;
            if(is_null($nomLieu)||is_null($rueLieu)){
                return $this->json(["error"=>"Les champs de lieux ne sont pas tous saisie"], 400);
            }
            $lieu= new Lieu($nomLieu,$rueLieu,$latitudeLieu,$longitudeLieu);
            $lieu->setVille($ville);
            $entityManager->persist($lieu);
            $entityManager->flush();
        }
        $idEtat= json_decode($jsonRecu)->idEtat;
        $etat = $etatRepository->find($idEtat);
        $sortie->setEtat($etat);
        $sortie->setLieu($lieu);
        $sortie->setCampus($campus);
        $sortie->setOrganisateur($organisateur);
        $error= $validator->validate($sortie);
        if(!is_null($error)>0){
            return $this->json($error,400);
        }
        $entityManager->persist($sortie);
        $entityManager->flush();
        return $this->json($sortie,201, [], ['groups'=>'sortie:read']);
    }
}
