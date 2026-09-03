<?php

namespace App\Controller;

use App\Entity\AlertesUsers;
use App\Entity\ClientEcbi;
use App\Entity\Commande;
use App\Entity\Proforma;
use App\Entity\TypeClient;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use http\Client;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ValidationController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/validateDoc/{type_doc}@{token}', name: 'app_validate_doc')]
    public function index(int $type_doc, string $token): Response
    {
        if ($type_doc == 1){
            $doc = $this->registry->getRepository(Proforma::class)->findOneBy(['id_sys'=>$token]);
            if ($doc){
                return $this->render('validation/index.html.twig',[
                    'doc'=> $doc,
                    'type_doc'=>$type_doc
               ]);
            }
        } elseif ($type_doc == 2) {
            $doc = $this->registry->getRepository(Commande::class)->findOneBy(['id_sys'=>$token]);
            if ($doc){
                return $this->render('validation/index.html.twig',[
                    'doc'=> $doc,
                    'type_doc'=>$type_doc
                ]);
            }
        } else {
            return $this->render('validation/index.html.twig',[
                'doc'=> null
            ]);
        }
    }

    #[Route('/MesValidationsDeProforma', name: 'validation_proforma')]
    public function validation_proforma(): Response
    {
        $alerte = $this->registry->getRepository(AlertesUsers::class)->findOneBy(['code_user'=>$this->getUser(), 'code_alerte'=>1]);
        if ($alerte){
            return $this->render('validation_proforma/index.html.twig',['proformas'=>$this->registry->getRepository(Proforma::class)->findBy(['etat'=>false],['date_proforma'=>"DESC"])]);
        } else {
            return $this->render('exceptions/no_granted.html.twig');
        }
    }
}
