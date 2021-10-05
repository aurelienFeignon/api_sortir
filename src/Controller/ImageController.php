<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/image/{nom}", name="image")
     */
    public function index($nom): Response
    {
        return $this->render('image/index.html.twig', [
            'nom' => $nom,
        ]);
    }
}
