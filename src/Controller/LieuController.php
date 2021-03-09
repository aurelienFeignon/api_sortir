<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/api/lieu", name="lieu", methods={"POST"})
     */
    public function index(Request $request, LieuRepository $lieuRepository): Response
    {
        $jsonRecu =$request->getContent();
        $idVille= json_decode($jsonRecu)->idVille;
        return $this->json($lieuRepository->findBy(['ville'=>$idVille]),200, [], ['groups'=>'lieu:read']);
    }
}
