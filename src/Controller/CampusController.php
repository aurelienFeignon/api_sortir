<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Campus;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CampusController extends AbstractController
{
    /**
     * @Route("api/campus", name="getCampus", methods={"GET"})
     */
    public function getCampus(CampusRepository $campusRepository): Response
    {
        return $this->json($campusRepository->findAll(),200, [], ['groups'=>'campusProduit:read']);
    }

    /**
     * @Route("api/campus", name="AddCampus", methods={"POST"})
     */
    public function addCampus(Request $request,EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $jsonRecu= $request->getContent();
        $campus= $serializer->deserialize($jsonRecu, Campus::class, 'json');
        $error= $validator->validate($campus);
        if(!is_null($error)){
            return $this->json($error,400);
        }else{
            $em->persist($campus);
            $em->flush();
            return $this->json($campus, 201);
        }
    }
}
