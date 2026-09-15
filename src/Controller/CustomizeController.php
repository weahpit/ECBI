<?php

namespace App\Controller;

use App\Entity\ColorApp;
use App\Entity\Customize;
use App\Entity\InfosSociete;
use App\Entity\Pays;
use App\Entity\User;
use App\Entity\Ville;
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
                'code'=>'success',
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
                'code'=>'error',
                'msg'=>'Erreur ! <br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse($reponse);
    }
#[Route('/getCouleurApp', name: 'get_couleur_app')]
    public function get_couleur_app(ManagerRegistry $registry): Response
    {
        $reponse = array();
        $data = array();
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        try {
            $couleurs = $registry->getRepository(ColorApp::class)->findAll();
            foreach ($couleurs as $couleur){
                $data[] = array(
                    'id'=>$couleur->getId(),
                    'libelle'=>$couleur->getLibelle(),
                    'background'=>$couleur->getBackground(),
                    'textcolor'=>$couleur->getTextColor(),
                );
            }
            $reponse = array(
                'code'=>'success',
                'msg'=>'Success',
                'data'=>$data
            );
        } catch (\Throwable $throwable){
            $reponse = array(
                'code'=>'error',
                'msg'=>'Erreur ! <br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse(json_encode($reponse));
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
                    'code'=>'success',
                    'msg'=>'Success',
                    'id'=>$customise->getId(),
                    'police'=>$customise->getPolice(),
                    'icone_application'=>$customise->getIconeApplication(),
                    'favicon'=>$customise->getFavicon(),
                    'logo_login'=>$customise->getLogoLogin(),
                    'couleur_barre_titre'=>$customise->getNavbarFont() ? $customise->getNavbarFont()->getBackground() : "0",
                    'prefix_application'=>$customise->getPrefixApplication(),
                    'type_icone_application'=>$customise->getTypeIconeApplication(),
                    'fond_application'=>$customise->getFondApplication(),
                    'type_fond_application'=>$customise->getTypeFond(),
                    'entete'=>$customise->getEnteteDoc()
                );
            } else {
                $reponse = array(
                    'code'=>'info',
                    'msg'=>'Saisissez les données de paramétrage de l\'application'
                );
            }

        } catch (\Throwable $throwable){
            $reponse = array(
                'code'=>'error',
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

                $Societe = $registry->getRepository(Customize::class)->findOneBy([]);
                $navbarFont = $registry->getRepository(ColorApp::class)->findOneBy(['background'=>$couleur_barre_titre]);

                if (!$Societe) {$infosSociete = new Customize();} else { $infosSociete = $Societe;}

                $infosSociete->setPolice($police);
                $infosSociete->setTypeIconeApplication($type_icone);
                $infosSociete->setBarreTitreCouleur($couleur_barre_titre);
                if ($navbarFont){$infosSociete->setNavbarFont($navbarFont);}
                $infosSociete->setPrefixApplication($prefix_application);

                // Enregistrement de l'icone de lapplication
                if ($type_icone == "Font_Awesome") {
                    $infosSociete->setIconeApplication($icone_application);
                } else {
                    // Charger l'icone de l'application
                    $filename_icone = $request->files->get('filename_icone');
                    if ($filename_icone) {
                        $filename = 'icone_app.'.$filename_icone->guessExtension();
                        $filename_icone->move($this->getParameter('icone_app'), $filename);
                        $infosSociete->setIconeApplication($filename);
                    }
                }

            // Enregistrement du favicon
            $filename_favicon = $request->files->get('filename_favicon');
            if ($filename_favicon) {
                $filename = 'favicon.'.$filename_favicon->guessExtension();
                $filename_favicon->move($this->getParameter('favicon'), $filename);
                $infosSociete->setFavicon($filename);
            }

        // Enregistrement du logo login
            $filename_logo_login = $request->files->get('filename_logo_login');
            if ($filename_logo_login) {
                $filename = 'logo_login.'.$filename_logo_login->guessExtension();
                $filename_logo_login->move($this->getParameter('logo_login'), $filename);
                $infosSociete->setLogoLogin($filename);
            }

            // Enregistrement de l'entete document de l'entreprise
            $filename_entete = $request->files->get('filename_entete');
            if ($filename_entete) {
                $filename = 'entete.'.$filename_entete->guessExtension();
                $filename_entete->move($this->getParameter('entete'), $filename);
                $infosSociete->setEnteteDoc($filename);
            }


            // Enregistrement de le background de l'application
            if ($type_fond == "Couleur") {
                $infosSociete->setFondApplication($fond_application);
            } else {
                // Charger l'icone de l'application
                $filename_fond = $request->files->get('filename_fond');
                if ($filename_fond) {
                    $filename = 'fond_app.'.$filename_fond->guessExtension();
                    $filename_fond->move($this->getParameter('fond_app'), $filename);
                    $infosSociete->setFondApplication($filename);
                }
            }
                $infosSociete->setTypeFond($type_fond);

                $registry->getManager()->persist($infosSociete);
                $registry->getManager()->flush();

                $reponse = array('code'=>'success', 'msg'=>'Données de Personnalisation de l\'application mises à jour avec avec succès !');
            } catch (\Throwable $throwable){
            $reponse = array('code'=>'error', 'msg'=>'Une erreur s\'est produite !<br>'.$throwable->getMessage());
        }

        return new JsonResponse($reponse);
    }

    #[Route('/saveInfosSociete', name: 'save_infos_societe')]
    public function save_infos_societe(Request $request, ManagerRegistry $registry): Response
    {
        $reponse = array();
       /* try {*/
            $rs = $request->request->get('rs');
            $sigle = $request->request->get('sigle');
            $emailSociete = $request->request->get('email_societe');
            $pays = (int) $request->request->get('pays');
            $ville =  (int) $request->request->get('ville');
            $adresse = $request->request->get('adresse');
            $tel = $request->request->get('tel');
            $mobile = $request->request->get('mobile');
            $siteweb = $request->request->get('siteweb');
            $cc = $request->request->get('cc');
            $rccim = $request->request->get('rccim');
            $type_imposition = $request->request->get('type_imposition');
            $centre_impots = $request->request->get('centre_impots');
            $secteur = $request->request->get('secteur');

            $Pays = $registry->getRepository(Pays::class)->find($pays);
            $Ville = $registry->getRepository(Ville::class)->find($ville);

            if (!$Pays || !$Ville || !$rs || !$sigle || !$emailSociete){
                $reponse = array('code'=>'error', 'msg'=>'Merci de renseigner les infos de la société !');
                return new JsonResponse($reponse);
            }
            $infosSociete = $registry->getRepository(InfosSociete::class)->findOneBy([]);
            if (!$infosSociete) {$infosSociete = new InfosSociete();}

            $infosSociete->setRaisonSociale(strtoupper($rs));
            $infosSociete->setSigle(strtoupper($sigle));
            $infosSociete->setSiteweb($siteweb);
            $infosSociete->setAdresse($adresse);
            $infosSociete->setSecteurActivite(strtoupper($secteur));
            $infosSociete->setCentreImpots(strtoupper($centre_impots));
            $infosSociete->setTypeImposition(strtoupper($type_imposition));
            $infosSociete->setCc(strtoupper($cc));
            $infosSociete->setRccim(strtoupper($rccim));
            $infosSociete->setMobile($mobile);
            $infosSociete->setEmail(strtolower($emailSociete));
            $infosSociete->setTel($tel);
            $infosSociete->setCodePays($Pays);
            $infosSociete->setCodeVille($Ville);
            // Enregistrement du logo de la société

                // Charger l'icone de l'application
                $filename_logo = $request->files->get('logoUpload');
                if ($filename_logo) {
                    $filename = 'logo.'.$filename_logo->guessExtension();
                    $filename_logo->move($this->getParameter('logo'), $filename);
                    $infosSociete->setLogo($filename);
                }

            $registry->getManager()->persist($infosSociete);
            $registry->getManager()->flush();

            $reponse = array('code'=>'success', 'msg'=>'Données de Personnalisation de l\'application mises à jour avec avec succès !');
       /* } catch (\Throwable $throwable){
            $reponse = array('code'=>'error', 'msg'=>'Une erreur s\'est produite !<br>'.$throwable->getMessage());
        }*/

        return new JsonResponse($reponse);
    }

    #[Route('/saveInfosSuperAdmin', name: 'save_infos_super_admin')]
    public function save_infos_super_admin(Request $request, ManagerRegistry $registry): Response
    {
        $reponse = array();
        /*try {*/
            $nom = $request->request->get('nom');
            $email = $request->request->get('email');
            $prenoms = $request->request->get('prenoms');
            $mdp = $request->request->get('motpasse');
            $comfirme =  $request->request->get('comfirmeMotpasse');

            $user = $registry->getRepository(User::class)->findOneBy(['email'=>$email]);

            if ($user){
                $reponse = array('code'=>'error', 'msg'=>'Email existant');
            } elseif ($mdp != $comfirme){
                $reponse = array('code'=>'error', 'msg'=>'Mots de passe non conforme !');
            } elseif (!$nom || !$prenoms || !$mdp) {
                $reponse = array('code' => 0, 'msg' => 'Merci de renseigner les informations de l\'Admin !');
            }else{
                $adminUser = new User();
                $adminUser->setNom(strtoupper($nom));
                $adminUser->setPrenoms(strtoupper($prenoms));
                $adminUser->setEmail($email);
                $adminUser->setRoles(["ROLE_SUPER_ADMIN","ROLE_ADMIN","ROLE_USER"]);
                $adminUser->setPassword(password_hash($mdp, PASSWORD_BCRYPT));

                $registry->getManager()->persist($adminUser);
                $registry->getManager()->flush();

                $reponse = array('code'=>'success', 'msg'=>'Admin enregistré avec Succès !');
            }

        /*} catch (\Throwable $throwable){
            $reponse = array('code'=>'error', 'msg'=>'Une erreur s\'est produite !<br>'.$throwable->getMessage());
        }*/

        return new JsonResponse($reponse);
    }
}
