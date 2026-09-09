<?php

namespace App\Controller;

use App\Entity\GrilleTarif;
use App\Entity\Produit;
use App\Entity\TarifProduitGrille;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {

        return $this->render('produit/index.html.twig');
    }

    #[Route('/getProduits', name: 'get_produits')]
    public function get_produits(): Response
    {
        $reponse = array();
        try {
            $produits = $this->registry->getRepository(Produit::class)->findAll();
            $data = array();
            foreach ($produits as $produit){
                $data[] = array(
                    'libelle_produit'=>$produit->getLibelle(),
                    'id'=>$produit->getId(),
                    'code_produit'=>$produit->getCodeProduit()
                );
            }

            sort($data);
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
    #[Route('/getOnlyProduitsFromGrille/{value}', name: 'get_only_produits_from_grille')]
        public function get_only_produits_from_grille(int $value): Response
        {
            $reponse = array();
            try {
                $grille = $this->registry->getRepository(GrilleTarif::class)->find($value);
                $produits = $this->registry->getRepository(Produit::class)->findAll();
                $data = array();
                if($grille){
                    foreach ($produits as $produit){
                        // Reche si le produit est dans la grille sélectionnée
                        $listetarif = $this->registry->getRepository(TarifProduitGrille::class)->findOneBy(['code_produit'=>$produit, 'code_grille'=>$grille]);

                        if($listetarif){
                                $data[] = array(
                                    'id'=>$produit->getId(),
                                    'libelle_produit'=>$produit->getLibelle(),
                                    'code_produit'=>$produit->getCodeProduit()
                                );
                        }
                    }
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

    #[Route('/getOnlyProduitsOutofGrille/{value}', name: 'get_only_produits_outof_grille')]
    public function get_only_produits_outof_grille(int $value): Response
    {
        $reponse = array();
        try {
            $grille = $this->registry->getRepository(GrilleTarif::class)->find($value);
            $produits = $this->registry->getRepository(Produit::class)->findAll();
            $data = array();
            if($grille){
                foreach ($produits as $produit){
                    // Reche si le produit est dans la grille sélectionnée
                    $listetarif = $this->registry->getRepository(TarifProduitGrille::class)->findOneBy(['code_produit'=>$produit, 'code_grille'=>$grille]);

                    if(!$listetarif){
                        $data[] = array(
                            'id'=>$produit->getId(),
                            'libelle_produit'=>$produit->getLibelle(),
                            'code_produit'=>$produit->getCodeProduit()
                        );
                    }
                }
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

    #[Route('/getSingleProduit/{id_produit}', name: 'get_single_produit')]
    public function get_single_produit(int $id_produit): Response
    {
        $reponse = array();
        try {
            $produit = $this->registry->getRepository(Produit::class)->find($id_produit);

            if ($produit){
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Success',
                    'code_produit'=>$produit->getCodeProduit(),
                    'libelle_produit'=>$produit->getLibelle(),
                    'description_produit'=>$produit->getDescription(),
                );
            } else {
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Merci de sélectionner un produit dans la liste !'
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

    #[Route('/getTarifProduit/{id_produit}/{id_grille}', name: 'get_tarif_produit')]
    public function get_tarif_produit(int $id_produit, int $id_grille): Response
    {
        $reponse = array();
        try {
            $produit = $this->registry->getRepository(Produit::class)->find($id_produit);
            $grille = $this->registry->getRepository(GrilleTarif::class)->find($id_grille);

            if ($produit && $grille){
                $tarif = $this->registry->getRepository(TarifProduitGrille::class)->findOneBy(['code_produit'=>$produit, 'code_grille'=>$grille]);
                if ($tarif){
                    $reponse = array(
                        'code'=>1,
                        'msg'=>'Success',
                        'code_produit'=>$produit->getCodeProduit(),
                        'libelle_produit'=>$produit->getLibelle(),
                        'prix'=>$tarif->getTarif()
                    );
                } else {
                    $reponse = array(
                        'code'=>0,
                        'msg'=>'Le fichier Tarif n\'a pas été trouvé !'
                    );
                }

            } else {
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Merci de sélectionner un produit dans la liste !'
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


    #[Route('/saveProduit', name: 'produit_save')]
    public function produit_save(Request $request): Response
    {
        $reponse = [];
        try {
            $id_produit = $request->request->get('id_produit');
            $libelle = $request->request->get('libelle_produit');
            $code_produit = $request->request->get('code_produit');
            $description= $request->request->get('description_produit');

            if (!$libelle || !$code_produit) {
                $reponse = ['code' => 0, 'msg' => 'Merci de saisir tous les champs obligatoires !'];
            } else {
                $produit = $this->registry->getRepository(Produit::class)->find($id_produit);
                $isNew = false;
                if (!$produit) {
                           $produit = new Produit();
                           $isNew = true;
                       }

                $produit->setLibelle(strtoupper($libelle));
                $produit->setCodeProduit(strtoupper($code_produit));
                $produit->setDescription($description);

                $em = $this->registry->getManager();
                $em->persist($produit);
                $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => $isNew ? 'Produit créé avec succès !' : 'Produit mis à jour avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est produite !<br>' . $throwable->getMessage()];
        }
        return new JsonResponse($reponse);
    }
}
