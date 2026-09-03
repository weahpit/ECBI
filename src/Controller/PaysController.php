<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Ville;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PaysController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/pays', name: 'app_pays')]
    public function index(): Response
    {
        return $this->render('pays/index.html.twig');
    }

    #[Route('/getPays', name: 'get_pays')]
    public function get_payss(): Response
    {
        $reponse = array();
        try {
            $payss = $this->registry->getRepository(Pays::class)->findAll();
            $data = array();
            foreach ($payss as $clt){
                $data[] = array(
                    'id'=>$clt->getId(),
                    'libelle'=>$clt->getLibelle()
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

    #[Route('/getSinglePays/{id_pays}', name: 'get_single_pays')]
    public function get_single_pays(int $id_pays): Response
    {
        $reponse = array();
        try {
            $pays = $this->registry->getRepository(Pays::class)->find($id_pays);

            if ($pays){
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Success',
                    'libelle'=>$pays->getLibelle()
                );
            } else {
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Merci de sélectionner un pays dans la liste !'
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

    #[Route('/getVillesByPays/{id_pays}', name: 'get_villes_pays')]
    public function get_villes_pays(int $id_pays): Response
        {
            $reponse = array();
            $data = array();
            try {
                $pays = $this->registry->getRepository(Pays::class)->find($id_pays);
                if ($pays){
                    $villes = $this->registry->getRepository(Ville::class)->findBy(['code_pays'=>$pays]);
                    foreach ($villes as $ville){
                        $data[] = array(
                            'id'=>$ville->getId(),
                            'libelle'=>$ville->getLibelle()
                        );
                    }
                    $reponse = array(
                        'code'=>1,
                        'msg'=>'Success',
                        'data'=>$data
                    );
                } else {
                    $reponse = array(
                        'code'=>2,
                        'msg'=>'Aucun pays sélectionné !',
                        'data'=>$data
                    );
                }

            }catch (\Throwable $throwable){
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Erreur !<br>'. $throwable->getMessage()
                );
            }
            return new JsonResponse(json_encode($reponse));
        }

    #[Route('/savePays', name: 'pays_save')]
    public function pays_save(Request $request): Response
    {
        $reponse = [];
        try {
            $id_pays = $request->request->get('id_pays');
            $libelle_pays = $request->request->get('libelle_pays');

            if (!$libelle_pays) {
                $reponse = ['code' => 0, 'msg' => 'Merci de renseigner le pays !'];
            } else {
                $pays = $this->registry->getRepository(Pays::class)->find($id_pays);
                $isNew = false;

                if (!$pays) {
                    $pays = new Pays();
                    $isNew = true;
                }

                    $pays->setLibelle(strtoupper($libelle_pays));
                    $em = $this->registry->getManager();
                    $em->persist($pays);
                    $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => $isNew ? 'Pays créé avec succès !' : 'Pays mis à jour avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est produite !<br>' . $throwable->getMessage()];
        }

        return new JsonResponse($reponse);
    }

    #[Route('/saveVille', name: 'ville_save')]
    public function ville_save(Request $request): Response
    {
        $reponse = [];
        try {
            $id_pays = $request->request->get('id_pays');
            $libelle_ville = $request->request->get('libelle_ville');

            $pays = $this->registry->getRepository(Pays::class)->find($id_pays);
            if (!$libelle_ville || !$pays) {
                $reponse = ['code' => 0, 'msg' => 'Merci de renseigner le ville !'];
            } else {
                    $ville = new Ville();

                    $ville->setLibelle(strtoupper($libelle_ville));
                    $ville->setCodePays($pays);
                    $em = $this->registry->getManager();
                    $em->persist($ville);
                    $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' =>  'Ville créé avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est produite !<br>' . $throwable->getMessage()];
        }

        return new JsonResponse($reponse);
    }
}
