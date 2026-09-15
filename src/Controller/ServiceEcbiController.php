<?php

namespace App\Controller;

use App\Entity\ServiceEcbi;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceEcbiController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/getServiceEcbis', name: 'get_service_ecbis')]
    public function get_service_ecbis(): Response
    {
        $reponse = array();
        try {
            $service_ecbis = $this->registry->getRepository(ServiceEcbi::class)->findAll();
            $data = array();
            foreach ($service_ecbis as $service_ecbi){
                $data[] = array(
                    'libelle'=>$service_ecbi->getLibelle(),
                    'id'=>$service_ecbi->getId()
                );
            }

            sort($data);
            $reponse = array(
                'code'=>'success',
                'msg'=>'Success',
                'data'=>$data
            );
        }catch (\Throwable $throwable){
            $reponse = array(
                'code'=>'error',
                'msg'=>'Erreur !<br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse(json_encode($reponse));
    }

    #[Route('/getSingleServiceEcbi/{id_service_ecbi}', name: 'get_single_service_ecbi')]
    public function get_single_service_ecbi(int $id_service_ecbi): Response
    {
        $reponse = array();
        try {
            $service_ecbi = $this->registry->getRepository(ServiceEcbi::class)->find($id_service_ecbi);

            if ($service_ecbi){
                $reponse = array(
                    'code'=>'success',
                    'msg'=>'Success',
                    'libelle'=>$service_ecbi->getLibelle()
                );
            } else {
                $reponse = array(
                    'code'=>'error',
                    'msg'=>'Merci de sélectionner un service_ecbi dans la liste !'
                );
            }

        }catch (\Throwable $throwable){
            $reponse = array(
                'code'=>'error',
                'msg'=>'Erreur !<br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse($reponse);
    }
    #[Route('/saveServiceEcbi', name: 'service_ecbi_save')]
    public function service_ecbi_save(Request $request): Response
    {
        $reponse = [];
        try {
            $id_service_ecbi = (int) $request->request->get('id_service_ecbi');
            $libelle = $request->request->get('libelle_service_ecbi');

            if (!$libelle) {
                $reponse = ['code' => 0, 'msg' => 'Merci de saisir tous les champs obligatoires !'];
            } else {
                $service_ecbi = $this->registry->getRepository(ServiceEcbi::class)->find($id_service_ecbi);
                $isNew = false;
                if (!$service_ecbi) {
                           $service_ecbi = new ServiceEcbi();
                           $isNew = true;
                       }

                $service_ecbi->setLibelle(strtoupper($libelle));

                $em = $this->registry->getManager();
                $em->persist($service_ecbi);
                $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => $isNew ? 'Service créé avec succès !' : 'Service mis à jour avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est service_ecbie !<br>' . $throwable->getMessage()];
        }
        return new JsonResponse($reponse);
    }
}
