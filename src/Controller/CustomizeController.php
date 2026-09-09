<?php

namespace App\Controller;

use App\Entity\Customize;
use App\Entity\InfosSociete;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CustomizeController extends AbstractController
{
    #[Route('/getSocieteInfos', name: 'getSocieteInfos')]
    public function getSocieteInfos(ManagerRegistry $registry): Response
    {
        $reponse = array();
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        try {
            $infosSociete = $registry->getRepository(InfosSociete::class)->findOneBy([]);
            $reponse = array(
                'code'=>1,
                'msg'=>'Success',
                'id'=>$infosSociete->getId(),
                'sigle'=>$infosSociete->getSigle(),
                'rs'=>$infosSociete->getRaisonSociale(),
                'rccim'=>$infosSociete->getRccim(),
                'mobile'=>$infosSociete->getMobile(),
                'tel'=>$infosSociete->getTel(),
                'cc'=>$infosSociete->getCc(),
                'adresse'=>$infosSociete->getAdresse(),
                'email'=>$infosSociete->getEmail(),
                'logo'=>$infosSociete->getLogo(),
                'centre_impots'=>$infosSociete->getCentreImpots(),
                'type_impots'=>$infosSociete->getTypeImposition(),
            );
        } catch (\Throwable $throwable){
            $reponse = array(
                'code'=>0,
                'msg'=>'Erreur ! <br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse($reponse);
    }

    #[Route('/getCustomInfos', name: 'getCustomInfos')]
    public function getCustomInfos(ManagerRegistry $registry): Response
    {
        $reponse = array();
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        try {
            $customise = $registry->getRepository(Customize::class)->findOneBy([]);
            if ($customise){
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Success',
                    'id'=>$customise->getId(),
                    'police'=>$customise->getPolice(),
                    'icone_application'=>$customise->getIconeApplication(),
                    'favicon'=>$customise->getFavicon(),
                    'logo_login'=>$customise->getLogoLogin(),
                    'couleur_barre_titre'=>$customise->getBarreTitreCouleur(),
                    'prefix_application'=>$customise->getPrefixApplication(),
                    'type_icone_application'=>$customise->getTypeIconeApplication(),
                    'fond_application'=>$customise->getFondApplication(),
                    'type_fond_application'=>$customise->getTypeFond(),
                    'entete'=>$customise->getEnteteDoc()
                );
            } else {
                $reponse = array(
                    'code'=>3,
                    'msg'=>'Saisissez les données de paramétrage de l\'application'
                );
            }

        } catch (\Throwable $throwable){
            $reponse = array(
                'code'=>0,
                'msg'=>'Erreur ! <br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse($reponse);
    }

    #[Route('/saveInfosCustomize', name: 'save_infos_customize')]
    public function save_infos_customize(Request $request, ManagerRegistry $registry): Response
    {
        $reponse = array();
        try {

                $type_icone = $request->request->get('type_icone');
                $police = $request->request->get('police');
                $icone_application = $request->request->get('icone_application');
                $couleur_barre_titre = $request->request->get('couleur_barre_titre');
                $prefix_application = $request->request->get('prefix_application');
                $type_fond = $request->request->get('type_fond');
                $fond_application = $request->request->get('fond_application');

                $infosCustomize = $registry->getRepository(Customize::class)->findOneBy([]);
                if (!$infosCustomize) {$infosCustomize = new Customize();}

                $infosCustomize->setPolice($police);
                $infosCustomize->setTypeIconeApplication($type_icone);
                $infosCustomize->setBarreTitreCouleur($couleur_barre_titre);
                $infosCustomize->setPrefixApplication($prefix_application);

                // Enregistrement de l'icone de lapplication
                if ($type_icone == "Font_Awesome") {
                    $infosCustomize->setIconeApplication($icone_application);
                } else {
                    // Charger l'icone de l'application
                    $filename_icone = $request->files->get('filename_icone');
                    if ($filename_icone) {
                        $filename = 'icone_app.'.$filename_icone->guessExtension();
                        $filename_icone->move($this->getParameter('icone_app'), $filename);
                        $infosCustomize->setIconeApplication($filename);
                    }
                }

            // Enregistrement du favicon
            $filename_favicon = $request->files->get('filename_favicon');
            if ($filename_favicon) {
                $filename = 'favicon.'.$filename_favicon->guessExtension();
                $filename_favicon->move($this->getParameter('favicon'), $filename);
                $infosCustomize->setFavicon($filename);
            }

        // Enregistrement du logo login
            $filename_logo_login = $request->files->get('filename_logo_login');
            if ($filename_logo_login) {
                $filename = 'logo_login.'.$filename_logo_login->guessExtension();
                $filename_logo_login->move($this->getParameter('logo_login'), $filename);
                $infosCustomize->setLogoLogin($filename);
            }

            // Enregistrement de l'entete document de l'entreprise
            $filename_entete = $request->files->get('filename_entete');
            if ($filename_entete) {
                $filename = 'entete.'.$filename_entete->guessExtension();
                $filename_entete->move($this->getParameter('entete'), $filename);
                $infosCustomize->setEnteteDoc($filename);
            }


            // Enregistrement de le background de l'application
            if ($type_fond == "Couleur") {
                $infosCustomize->setFondApplication($fond_application);
            } else {
                // Charger l'icone de l'application
                $filename_fond = $request->files->get('filename_fond');
                if ($filename_fond) {
                    $filename = 'fond_app.'.$filename_fond->guessExtension();
                    $filename_fond->move($this->getParameter('fond_app'), $filename);
                    $infosCustomize->setIconeApplication($filename);
                }
            }

                $registry->getManager()->persist($infosCustomize);
                $registry->getManager()->flush();

                $reponse = array('code'=>1, 'msg'=>'Données de Personnalisation de l\'application mises à jour avec avec succès !');
            } catch (\Throwable $throwable){
            $reponse = array('code'=>0, 'msg'=>'Une erreur s\'est produite !<br>'.$throwable->getMessage());
        }

        return new JsonResponse($reponse);
    }

}
