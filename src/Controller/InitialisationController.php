<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InitialisationController extends AbstractController
{
    #[Route('/initialisation', name: 'app_initialisation')]
    public function index(): Response
    {
        return $this->render('initialisation/index.html.twig');
    }
}
