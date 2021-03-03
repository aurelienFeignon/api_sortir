<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Service\GenerateToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/api/register/participant", name="addParticipant", methods={"POST"})
     */
    public function addParticipant(Request $request, EntityManagerInterface $em, ValidatorInterface $validator,
                                   SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder,
                                   GenerateToken $generateToken, CampusRepository $campusRepository): Response
    {
        $jsonRecu= $request->getContent();
        $participant= $serializer->deserialize($jsonRecu, Participant::class, 'json');
        $id_campus=json_decode($jsonRecu)->campus;
        $campus= $campusRepository->find($id_campus);
        $participant->setCampus($campus);
        $participant= $generateToken->getToken($participant);
        $error= $validator->validate($participant);
        if(count($error)>0){
            return $this->json($error,400);
        }else{
            $participant->setPassword($passwordEncoder->encodePassword($participant, $participant->getPassword()));
            if($participant->getAdministrateur()){
                $participant->addRoles('ROLE_ADMIN');
            }
            $em->persist($participant);
            $em->flush();
            return $this->json($participant, 201, [], ['groups'=>'participant:read']);
        }
    }

    /**
     * @Route("/api/login/participant", name="loginParticipant", methods={"POST"})
     */
    public function seConnecter(Request $request, ParticipantRepository $participantRepository)
    {
        $jsonRecu= $request->getContent();
        $email= json_decode($jsonRecu)->email;
        $password= json_decode($jsonRecu)->password;
        $participant = $participantRepository->findOneBy(['email'=>$email]);
        if(empty($participant))
        {
            return $this->json(['error'=>'Email inconnu'], 404);
        }
        elseif (!password_verify($password, $participant->getPassword())){
        return $this->json(['error'=>'Le mot de passe est incorrect'],403);
        }
        else{
            return $this->json($participant,200,[], ['groups'=>'participantUser:read']);
        }
    }
    /**
     * @Route("/api/update/participant", name="updateParticipant", methods={"PUT"})
     */
    public function update(Request $request, EntityManagerInterface $em, ValidatorInterface $validator,
                           SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder,
                           ParticipantRepository $participantRepository)
    {
        $jsonRecu= $request->getContent();
        $participantUpdate= $serializer->deserialize($jsonRecu, Participant::class, 'json');
        $participant= $participantRepository->findOneBy(['id'=>$participantUpdate->getId()]);
        if(empty($participant)){
            return $this->json(["error"=>"l'utilisateur n'existe pas"],404);
        }
        if($participant->getNom()!==$participantUpdate->getNom())
        {
            $error=$validator->validateProperty($participantUpdate,'nom');
            if(count($error)>0){
                return $this->json($error, 400);
            }
            $participant->setNom($participantUpdate->getNom());
        }
        if($participant->getPrenom()!==$participantUpdate->getPrenom())
            {
                $error=$validator->validateProperty($participantUpdate,'prenom');
                if(count($error)>0){
                    return $this->json($error, 400);
                }
                $participant->setPrenom($participantUpdate->getPrenom());
            }
        if($participant->getEmail()!==$participantUpdate->getEmail())
        {
            $error=$validator->validateProperty($participantUpdate,'email');
            if(count($error)>0){
                return $this->json($error, 400);
            }
            $participant->setEmail($participantUpdate->getEmail());
        }
        if($participant->getUsername()!==$participantUpdate->getUsername())
        {
            $error=$validator->validateProperty($participantUpdate,'username');
            if(count($error)>0){
                return $this->json($error, 400);
            }
            $participant->setUsername($participantUpdate->geUsername());
        }
        //verifie si le champs password
        if(empty($participantUpdate->getPassword())||!password_verify($participantUpdate->getPassword(), $participant->getPassword()))
        {
            $error=$validator->validateProperty($participantUpdate,'password');
            if(count($error)>0){
                return $this->json($error, 400);
            }
            $participant->setPassword($passwordEncoder->encodePassword($participant, $participantUpdate->getPassword()));
        }
        dd($participant);
    }
}
/**
 * let administrateur: boolean= false
 * if(form.value.administrateur==="true"){
 * adminstrateur=true;
 * }
 */
