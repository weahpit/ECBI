<?php

namespace App\Controller;

use App\Entity\AlertesUsers;
use App\Entity\Customize;
use App\Entity\Produit;
use App\Entity\ProgrammationAlerte;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationsController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry)
    {
    }

    #[Route('/notifications', name: 'app_notifications')]
    public function index(): Response
    {
        $optionApp = $this->registry->getRepository(Customize::class)->findOneBy([]);
        $users =  $this->registry->getRepository(User::class)->findOneBy([]);

        if (!$optionApp || !$users){ return $this->redirectToRoute("app_initialisation");}
        if (!$this->getUser()){return  $this->redirectToRoute("app_login");}

        return $this->render('notifications/index.html.twig', [
            'controller_name' => 'NotificationsController',
        ]);
    }
    #[Route('/getUsersByNotification/{id_document}', name: 'get_users_by_notification')]
    public function get_users_by_notification(int $id_document): Response
    {
        $reponse = array();
        try {
            $data = array();
            $doc = $this->registry->getRepository(ProgrammationAlerte::class)->find($id_document);
            if ($doc){

                $usersAlertes = $this->registry->getRepository(AlertesUsers::class)->findBy(['code_alerte'=>$doc]);
                foreach ($usersAlertes as $alerte){
                    $data[] = array(
                        'nom_prenoms'=>$alerte->getCodeUser() ? $alerte->getCodeUser()->getNom() . " " . $alerte->getCodeUser()->getPrenoms() : "",
                        'email'=>$alerte->getCodeUser() ? $alerte->getCodeUser()->getEmail(): "",
                        'id'=>$alerte->getId()
                    );
                }

                sort($data);
                $reponse = array(
                    'code'=>'success',
                    'msg'=>'Success',
                    'data'=>$data
                );
            }



        }catch (\Throwable $throwable){
            $reponse = array(
                'code'=>'error',
                'msg'=>'Erreur !<br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse(json_encode($reponse));
    }

    #[Route('/getUsersHasNotNotification/{id_document}', name: 'get_users_has_not_notification')]
    public function get_users_has_not_notification(int $id_document): Response
    {
        $reponse = array();
        try {
            $data = array();
            $doc = $this->registry->getRepository(ProgrammationAlerte::class)->find($id_document);
            if ($doc){

                $users = $this->registry->getRepository(User::class)->findAll();
                foreach ($users as $user){
                    // recherche le User dans l'alerte du programme
                    $user_alerte = $this->registry->getRepository(AlertesUsers::class)->findOneBy(['code_alerte'=>$doc,'code_user'=>$user]);
                    if (!$user_alerte){
                        $data[] = array(
                            'nom_prenoms'=>$user->getNom() . " " . $user->getPrenoms(),
                            'id'=>$user->getId()
                        );
                    }

                }

                sort($data);
                $reponse = array(
                    'code'=>'success',
                    'msg'=>'Success',
                    'data'=>$data
                );
            }



        }catch (\Throwable $throwable){
            $reponse = array(
                'code'=>'error',
                'msg'=>'Erreur !<br>'. $throwable->getMessage()
            );
        }
        return new JsonResponse(json_encode($reponse));
    }

    #[Route('/getDocumentsNotifications', name: 'get_documents_notifications')]
    public function get_documents_notifications(): Response
    {
        $reponse = array();
        try {
            $data = array();

                $docNotifs = $this->registry->getRepository(ProgrammationAlerte::class)->findAll();
                foreach ($docNotifs as $docNotif){
                        $data[] = array(
                            'id'=>$docNotif->getId(),
                            'doc'=>$docNotif->getLibelleAlerte() ? : ""
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
#[Route('/SaveUserInListAlertesDoc/{id_user}/{id_programmation}', name: 'Save_user_in_list_alertes_doc')]
    public function Save_user_in_list_alertes_doc($id_user, $id_programmation): Response
    {
        $reponse = array();
        try {

                $user = $this->registry->getRepository(User::class)->find($id_user);
                $programmation = $this->registry->getRepository(ProgrammationAlerte::class)->find($id_programmation);
               if ($user && $programmation){
                    $alerte = new AlertesUsers();


                    $alerte->setCodeAlerte($programmation);
                    $alerte->setCodeUser($user);

                    $this->registry->getManager()->persist($alerte);
                    $this->registry->getManager()->flush();

                   $reponse = array(
                       'code'=>'success',
                       'msg'=>'Alerte enregistrée avec succès !'
                   );
               } else {
                   $reponse = array(
                       'code'=>'warning',
                       'msg'=>'Merci de sélectionner l\'utilisateur et le type alerte!'
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
}
