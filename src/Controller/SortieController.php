<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Etat;
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
        $sorties= $sortieRepository->findAll();
        $this->majBDD($sorties);
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

   /**
    * @Route("/api/sortie/inscription", name="inscriptionSortie", methods={"PUT"})
    */
    public function inscriptionSortie(  EntityManagerInterface $em, ParticipantRepository $participantRepository,
                                        SortieRepository $sortieRepository, Request $request): Response
    {
        $jsonRecu= $request->getContent();
        $idParticipant= json_decode($jsonRecu)->idParticipant;
        $participant= $participantRepository->find($idParticipant);
        if(is_null($participant)){
            return $this->json(["error"=>"Le participant n'existe pas"],400);
        }
        $idSortie= json_decode($jsonRecu)->idSortie;
        $sortie= $sortieRepository->find($idSortie);
        if(is_null($sortie)){
            return $this->json(["error"=>"La sortie n'existe pas"],400);
        }
        if(!$sortie->verifLimiteMax($sortie)){
            return $this->json(["error"=>"Le nombre de participant est depassé"], 400);
        }
        $sortie->addParticipant($participant);
        $em->flush();
        return $this->json($sortieRepository->findAll(),201,[],['groups'=>'sortie:read']);
    }
    /**
     * @Route("/api/sortie/deinscription", name="deinscriptionSortie", methods={"PUT"})
     */
    public function deinscriptionSortie(  EntityManagerInterface $em, ParticipantRepository $participantRepository,
                                        SortieRepository $sortieRepository, Request $request): Response
    {
        $jsonRecu= $request->getContent();
        $idParticipant= json_decode($jsonRecu)->idParticipant;
        $participant= $participantRepository->find($idParticipant);
        if(is_null($participant)){
            return $this->json(["error"=>"Le participant n'existe pas"],400);
        }
        $idSortie= json_decode($jsonRecu)->idSortie;
        $sortie= $sortieRepository->find($idSortie);
        if(is_null($sortie)){
            return $this->json(["error"=>"La sortie n'existe pas"],400);
        }
        if(!$sortie->verifParticipantInscrit($sortie, $participant)){
            return $this->json(["error"=>"Le participant n'est pas inscrit"], 400);
        }
        $sortie->removeParticipant($participant);
        $em->flush();
        return $this->json($sortieRepository->findAll(),201,[],['groups'=>'sortie:read']);
    }

    /**
     * @Route("/api/sortie/annuler", name="annulerSortie", methods={"PUT"})
     */
    public function annulerSortie(Request $request, EntityManagerInterface $em, EtatRepository $etatRepository, SortieRepository $sortieRepository)
    {
        $jsonRecu= $request->getContent();
        $idSortie= json_decode($jsonRecu)->id;
        $sortie= $sortieRepository->find($idSortie);
        if(is_null($sortie)){
            return $this->json(['error'=>"La sortie n'existe pas"], 400);
        }
        $motif= json_decode($jsonRecu)->motif;
        $etat= $etatRepository->find(2);
        $sortie->setEtat($etat);
        $sortie->setMotif($motif);
        $em->flush();
        return $this->json($sortieRepository->findAll(),201,[],['groups'=>'sortie:read']);
    }

    /**
     * @Route("/api/sortie/consulter", name="consulterSortie", methods={"POST"})
     */
     public function consulterSortie(Request $request, SortieRepository $sortieRepository){
         $jsonRecu= $request->getContent();
            $idSortie= json_decode($jsonRecu)->idSortie;
        return $this->json($sortieRepository->find($idSortie),200,[], ['groups'=>'sortie:read']);
     }

    private function majBDD(array $sorties)
    {
        $em= $this->getDoctrine()->getManager();
        foreach ($sorties as $sortie){
            $now= new \DateTime();
            if($sortie->getDateHeureDebut()->add(new \DateInterval('PT'.$sortie->getDuree().'M'))>$now){
            $repo= $this->getDoctrine()->getRepository(Etat::class);
            $etat= $repo->find(2);
            $sortie->setEtat($etat);
            $em->flush();
            }
            if($sortie->getDateHeureDebut()->add(new \DateInterval('P1M'))<$now)
            {
                $em->remove($sortie);
                $em->flush();
            }
        }
    }
}
