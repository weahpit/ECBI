<?php

namespace App\Controller;

use App\Entity\Customize;
use App\Entity\User;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ParametresController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }
    #[Route('/parametres', name: 'app_parametres')]
    public function index(): Response
    {
        $optionApp = $this->registry->getRepository(Customize::class)->findOneBy([]);
        $users =  $this->registry->getRepository(User::class)->findOneBy([]);

        if (!$optionApp || !$users){ return $this->redirectToRoute("app_initialisation");}
        if (!$this->getUser()){return  $this->redirectToRoute("app_login");}

        return $this->render('parametres/index.html.twig', [
            'controller_name' => 'ParametresController',
        ]);
    }
}
