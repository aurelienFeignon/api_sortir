<?php

namespace App\Controller;

use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class VilleController extends AbstractController
{
    /**
     * @Route("/api/villes", name="ville", methods={"GET"})
     */
    public function getVille(VilleRepository $villeRepository, SerializerInterface $serializer): Response
    {

        //$json= $serializer->serialize($villeRepository->findAll(),'json');
        //return new JsonResponse($json,200,[],true);
        return $this->json($villeRepository->findAll(),200, []);
    }
}
