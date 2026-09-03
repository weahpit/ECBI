<?php

namespace App\Controller;

use App\Entity\Qualite;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QualiteProduitController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/getQualiteProduits', name: 'get_qualite_produits')]
    public function get_qualite_produits(): Response
    {
        $reponse = array();
        try {
            $qualite_produits = $this->registry->getRepository(Qualite::class)->findAll();
            $data = array();
            foreach ($qualite_produits as $qualite_produit){
                $data[] = array(
                    'id'=>$qualite_produit->getId(),
                    'libelle'=>$qualite_produit->getLibelle()
                );
            }
            $reponse = array(
                'code'=>1,
                'msg'=>'Success',
                'data'=>$data
            );
        }catch (\Throwable $throwable){
            $reponse = array(
                'code'=>0,
                'msg'=>'Erreur !<br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse(json_encode($reponse));
    }

    #[Route('/getSingleQualiteProduit/{id_qualite_produit}', name: 'get_single_qualite_produit')]
    public function get_single_qualite_produit(int $id_qualite_produit): Response
    {
        $reponse = array();
        try {
            $qualite_produit = $this->registry->getRepository(Qualite::class)->find($id_qualite_produit);

            if ($qualite_produit){
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Success',
                    'libelle'=>$qualite_produit->getLibelle()
                );
            } else {
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Merci de sélectionner un qualite_produit dans la liste !'
                );
            }

        }catch (\Throwable $throwable){
            $reponse = array(
                'code'=>0,
                'msg'=>'Erreur !<br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse($reponse);
    }

    #[Route('/saveQualiteProduit', name: 'qualite_produit_save')]
    public function qualite_produit_save(Request $request): Response
    {
        $reponse = [];
        try {
            $id_qualite_produit = $request->request->get('id_qualite_produit');
            $libelle = $request->request->get('libelle');

            if (!$libelle) {
                $reponse = ['code' => 0, 'msg' => 'Merci de renseigner la qualité du produit !'];
            } else {
                $qualite_produit = $this->registry->getRepository(Qualite::class)->find($id_qualite_produit);
                $isNew = false;
                if (!$qualite_produit) {
                           $qualite_produit = new Qualite();
                           $isNew = true;
                       }

                $qualite_produit->setLibelle(strtoupper($libelle));

                $em = $this->registry->getManager();
                $em->persist($qualite_produit);
                $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => $isNew ? 'Qualité créée avec succès !' : 'Qualité mise à jour avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est qualite_produite !<br>' . $throwable->getMessage()];
        }
        return new JsonResponse($reponse);
    }
}
