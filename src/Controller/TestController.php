<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $tableau = [];
        for ($i=1; $i < 100 ; $i++)
        {
           print("$i\n");
        }
        return $this->json($tableau);
    }
}
