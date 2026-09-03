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

final class GrilleController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/grille', name: 'app_grille')]
    public function index(): Response
    {

        return $this->render('grille/index.html.twig');
    }

    #[Route('/getGrilles', name: 'get_grilles')]
    public function get_grilles(): Response
    {
        $reponse = array();
        try {
            $grilles = $this->registry->getRepository(GrilleTarif::class)->findAll();
            $data = array();

            foreach ($grilles as $grille){
                $tarifs_grille = array();
                // Recherche de tous les tarifs
                $tarifs = $this->registry->getRepository(TarifProduitGrille::class)->findBy(['code_grille'=>$grille]);
                foreach ($tarifs as $tarif){
                    $tarifs_grille[]  =array(
                        'id'=>$tarif->getId(),
                        'produit'=>$tarif->getCodeProduit() ? $tarif->getCodeProduit()->getLibelle(). "(". $tarif->getCodeProduit()->getCodeProduit() . ")" : "",
                        'tarif'=>$tarif->getTarif()
                    );
                }

                $data[] = array(
                    'id'=>$grille->getId(),
                    'libelle_grille'=>$grille->getLibelle(),
                    'tarifs'=>$tarifs_grille
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
    #[Route('/getGrilleContent/{value}', name: 'get_grille_content')]
        public function get_grille_content(int $value): Response
        {
            $reponse = array();
            try {
                $grille = $this->registry->getRepository(GrilleTarif::class)->find($value);
                $data = array();

                if ($grille){
                   // Recherche de tous les tarifs
                    $tarifs = $this->registry->getRepository(TarifProduitGrille::class)->findBy(['code_grille'=>$grille]);
                    foreach ($tarifs as $tarif){
                        $data[]  =array(
                            'id'=>$tarif->getId(),
                            'produit'=>$tarif->getCodeProduit() ? $tarif->getCodeProduit()->getLibelle(). "(". $tarif->getCodeProduit()->getCodeProduit() . ")" : "",
                            'tarif'=>$tarif->getTarif()
                        );
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

    #[Route('/getSingleGrille/{id_grille}', name: 'get_single_grille')]
    public function get_single_grille(int $id_grille): Response
    {
        $reponse = array();
        try {
            $grille = $this->registry->getRepository(GrilleTarif::class)->find($id_grille);

            if ($grille){
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Success',
                    'libelle_grille'=>$grille->getLibelle()
                );
            } else {
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Merci de sélectionner une grille dans la liste !'
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


    #[Route('/saveGrille', name: 'grille_save')]
    public function grille_save(Request $request): Response
    {
        $reponse = [];
        try {
            $id_grille = $request->request->get('id_grille');
            $libelle = $request->request->get('libelle_grille');

            //dd($libelle);
            if (!$libelle) {
                $reponse = ['code' => 0, 'msg' => 'Merci de saisir tous les champs obligatoires !'];
            } else {
                $grille = $this->registry->getRepository(GrilleTarif::class)->find($id_grille);
                $isNew = false;
                if (!$grille) {
                           $grille = new GrilleTarif();
                           $isNew = true;
                       }

                $grille->setLibelle(strtoupper($libelle));

                $em = $this->registry->getManager();
                $em->persist($grille);
                $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => $isNew ? 'Grille créée avec succès !' : 'Grille mise à jour avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est grillee !<br>' . $throwable->getMessage()];
        }
        return new JsonResponse($reponse);
    }

    #[Route('/saveTarif', name: 'tarif_save')]
    public function tarif_save(Request $request): Response
    {
        $reponse = [];
        try {
            $grille_code_produit = $request->request->get('grille_code_produit');
            $liste_grille = $request->request->get('liste_grille');
            $tarif = $request->request->get('tarif');

            //dd($tarif);
            $code_produit = $this->registry->getRepository(Produit::class)->find($grille_code_produit);
            $code_grille= $this->registry->getRepository(GrilleTarif::class)->find($liste_grille);

            //dd($libelle);
            if (!$code_produit || !$code_grille || !$tarif) {
                $reponse = ['code' => 0, 'msg' => 'Merci de saisir tous les champs obligatoires !'];
            } else {

                $newTarif = new TarifProduitGrille();
                $newTarif->setTarif((float) $tarif);
                $newTarif->setCodeProduit($code_produit);
                $newTarif->setCodeGrille($code_grille);
                $newTarif->setCreatedAt(new \DateTimeImmutable());
                if ($this->getUser()){ $newTarif->setCretatedBy($this->getUser());} else{$newTarif->setCretatedBy("NC");}

                $em = $this->registry->getManager();
                $em->persist($newTarif);
                $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => 'Taruf enregistré avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est grillee !<br>' . $throwable->getMessage()];
        }
        return new JsonResponse($reponse);
    }
}
