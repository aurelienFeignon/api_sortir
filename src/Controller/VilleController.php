<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VilleController extends AbstractController
{
    /**
     * @Route("/api/villes", name="getville", methods={"GET"})
     */
    public function getVille(VilleRepository $villeRepository): Response
    {

        return $this->json($villeRepository->findAll(),200, [], ['groups'=>'ville:read']);
    }

    /**
     * @Route("/api/villes", name="postville", methods={"POST"})
     */
    public function addVille(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer){
        $jsonRecu= $request->getContent();
        $ville= $serializer->deserialize($jsonRecu, Ville::class, 'json');
        $error= $validator->validate($ville);
        if(count($error)>0){
            return $this->json($error,400);
        }else{
            $em->persist($ville);
            $em->flush();
            return $this->json($ville, 201,[],['groups'=>'"ville:read"']);
        }
    }
}
