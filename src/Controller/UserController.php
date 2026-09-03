<?php

namespace App\Controller;

use App\Entity\GrilleTarif;
use App\Entity\ServiceEcbi;
use App\Entity\User;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {

        return $this->render('user/index.html.twig');
    }

    #[Route('/getUsers', name: 'get_users')]
    public function get_users(): Response
    {
        $reponse = array();
        try {
            $users = $this->registry->getRepository(User::class)->findAll();
            $data = array();
            foreach ($users as $user){
                $data[] = array(
                    'nom_prenoms'=>$user->getNom(). " " . $user->getPrenoms(),
                    'id'=>$user->getId(),
                    'email'=>$user->getEmail(),
                    'mobile'=>$user->getMobile(),
                    'service'=>$user->getCodeService() ? $user->getCodeService()->getLibelle() : "" ,
                    'poste'=>$user->getFonction(),
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

    #[Route('/getSingleUser/{id_user}', name: 'get_single_user')]
    public function get_single_user(int $id_user): Response
    {
        $reponse = array();
        try {
            $user = $this->registry->getRepository(User::class)->find($id_user);

            if ($user){
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Success',
                    'nom_prenoms'=>$user->getNom(). " " . $user->getPrenoms(),
                    'nom'=>$user->getNom(),
                    'prenoms'=>$user->getPrenoms(),
                    'id'=>$user->getId(),
                    'email_user'=>$user->getEmail(),
                    'mobile'=>$user->getMobile(),
                    'service'=>$user->getCodeService() ? $user->getCodeService()->getId() : "" ,
                    'poste'=>$user->getFonction(),
                    'titre'=>$user->getTitre(),
                );
            } else {
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Merci de sélectionner un utilisateur dans la liste !'
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


    #[Route('/saveUser', name: 'user_save')]
    public function user_save(Request $request): Response
    {
        $reponse = [];
        try {
            $id_user =(int) $request->request->get('id_user');
            $nom = $request->request->get('nom');
            $prenoms = $request->request->get('prenoms');
            $email = $request->request->get('email');
            $mobile = $request->request->get('mobile_user');
            $id_serviceEcbi =  $request->request->get('serviceEcbi')? (int) $request->request->get('serviceEcbi') :0 ;
            $poste = $request->request->get('poste');
            $mot_passe = $request->request->get('mot_passe');
            $confirme_mot_passe = $request->request->get('confirme_mot_passe');
            $titre = $request->request->get('titre');

           $serviceEcbi = $this->registry->getRepository(ServiceEcbi::class)->find($id_serviceEcbi);
            // dd($mobile);
            if (!$nom || !$prenoms || !$email || !$mobile) {
                $reponse = ['code' => 2, 'msg' => 'Merci de saisir tous les champs obligatoires !'];
            }elseif (!$id_user && !$mot_passe) {
                $reponse = ['code' => 2, 'msg' => 'le mot de passe est obligatoire !'];
            } elseif ($mot_passe != $confirme_mot_passe && $id_user == 0 ) {
                $reponse = ['code' => 2, 'msg' => 'Les mots de passe saisis ne sont pas conforme !'];
            }else{
                $user = $this->registry->getRepository(User::class)->find($id_user);
                $isNew = false;
                if (!$user) {
                           $user = new User();
                           $isNew = true;
                           $user->setPassword(password_hash($mot_passe,PASSWORD_BCRYPT));
                           $user->setRoles(['ROLE_USER']);
                       }

                $user->setEmail($email);
                $user->setNom(strtoupper($nom));
                $user->setPrenoms(strtoupper($prenoms));
                $user->setMobile($mobile);
                $user->setFonction($poste);
                if ($serviceEcbi){$user->setCodeService($serviceEcbi);}
                $user->setTitre($titre);

                $em = $this->registry->getManager();
                $em->persist($user);
                $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => $isNew ? 'Utilisateur créé avec succès !' : 'Utilisateur mis à jour avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est usere !<br>' . $throwable->getMessage()];
        }
        return new JsonResponse($reponse);
    }

    #[Route('/changeMdp', name: 'change_mdp')]
    public function change_mdp(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $reponse = [];
        try {
            $id_user =(int) $request->request->get('id_user');
            $ancien = $request->request->get('ancien');
            $nouveau = $request->request->get('nouveau');
            $confirme = $request->request->get('confirme');

           $user = $this->registry->getRepository(User::class)->find($id_user);
            // dd($ancien);
            if (!$ancien || !$nouveau || !$confirme || !$user) {
                $reponse = ['code' => 0, 'msg' => 'Merci de sélectionner l\'utilisateur  puis saisir tous les champs obligatoires !'];
            } elseif (!$passwordHasher->isPasswordValid($user, $ancien)) {
                $reponse = ['code' => 2, 'msg' => 'L\'ancien mot de passe n\'est pas correct !'];
            }elseif ($nouveau != $confirme) {
                $reponse = ['code' => 2, 'msg' => 'Le nouveau mot de passe n\'a pas été confirmé!'];
            }else{
                $user->setPassword(password_hash($nouveau,PASSWORD_BCRYPT));

                $em = $this->registry->getManager();
                $em->persist($user);
                $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => 'Mot de passe modifié avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est usere !<br>' . $throwable->getMessage()];
        }
        return new JsonResponse($reponse);
    }
}
