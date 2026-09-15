<?php

namespace App\Controller;

use App\Entity\Customize;
use App\Entity\Notification;
use App\Entity\Proforma;
use App\Entity\User;
use App\Services\NotificationService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TdbController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry)
    {
    }

    #[Route('/tdb', name: 'app_tdb')]
    public function index(): Response
    {
        $optionApp = $this->registry->getRepository(Customize::class)->findOneBy([]);
        $users =  $this->registry->getRepository(User::class)->findOneBy([]);

        if (!$optionApp || !$users){ return $this->redirectToRoute("app_initialisation");}
        if (!$this->getUser()){return  $this->redirectToRoute("app_login");}

        $proforma_en_attente = $this->registry->getRepository(Proforma::class)->count(['etat'=>false]);
        $proforma_validees = $this->registry->getRepository(Proforma::class)->count(['etat'=>true]);
        $clients = $this->registry->getRepository(Proforma::class)->count(['etat'=>false]);

        return $this->render('tdb/index.html.twig', [
            'pf_attente' => $proforma_en_attente,
            'pf_validees' => $proforma_validees,
            'clients' => $clients
        ]);
    }

    #[Route('/getCurrentNotifs', name: 'app_notifs')]
    public function getCurrentNotifs(ManagerRegistry $registry): Response
    {
        $reponse = array();
        $data = array();
        $nbNotifs = $registry->getRepository(Notification::class)->count(['destinataire'=>$this->getUser(), 'lu'=>false]);
        if ($nbNotifs > 0){
            $listeNotific = $registry->getRepository(Notification::class)->findBy(['destinataire'=>$this->getUser(), 'lu'=>false]);
            foreach($listeNotific as $alerte){
               $data[] = array(
                   'id'=>$alerte->getId(),
                   'date_notif'=>$alerte->getCreatedAt()->format("d/m/y h:i:s"),
                   'title'=>$alerte->getLibelle(),
                   'msg'=>$alerte->getDescription(),
                   'lien'=>$alerte->getTarget()
               );
            }
            rsort($data);
        }
        $reponse = array('nbNotifs'=>$nbNotifs, 'data'=>$data);
        return new JsonResponse(json_encode($reponse));
    }

    #[Route('/setLu/{id_notifi}', name: 'set_lu')]
    public function set_lu(ManagerRegistry $registry, int $id_notifi): Response
    {
        $reponse = array();
        $data = array();
        $notif = $registry->getRepository(Notification::class)->find($id_notifi);
        if ($notif){
            $notif->setLu(true);
            $registry->getManager()->persist($notif);
            $registry->getManager()->flush();
        }
        $reponse = array('code'=>'success', 'msg'=>'SUCCESS');
        return new JsonResponse($reponse);
    }

}
