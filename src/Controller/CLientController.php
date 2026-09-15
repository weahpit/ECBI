<?php

namespace App\Controller;

use App\Entity\ClientEcbi;
use App\Entity\Customize;
use App\Entity\Pays;
use App\Entity\TypeClient;
use App\Entity\User;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use http\Client;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CLientController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/client', name: 'app_client')]
    public function index(): Response
    {
        $optionApp = $this->registry->getRepository(Customize::class)->findOneBy([]);
        $users =  $this->registry->getRepository(User::class)->findOneBy([]);

        if (!$optionApp || !$users){ return $this->redirectToRoute("app_initialisation");}
        return $this->render('client/index.html.twig');
    }

    #[Route('/getClients', name: 'get_clients')]
    public function get_clients(): Response
    {
        $reponse = array();
        try {
            $clients = $this->registry->getRepository(ClientEcbi::class)->findAll();
            $data = array();
            foreach ($clients as $clt){
                $data[] = array(
                    'id'=>$clt->getId(),
                    'rs'=>$clt->getSigle(),
                    'contacts'=>$clt->getMobile(),
                    'code'=>$clt->getCode()
                );
            }
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

    #[Route('/getSingleClient/{id_client}', name: 'get_single_client')]
    public function get_single_client(int $id_client): Response
    {
        $reponse = array();
        try {
            $client = $this->registry->getRepository(ClientEcbi::class)->find($id_client);

            if ($client){
                $reponse = array(
                    'code'=>'success',
                    'msg'=>'Success',
                    'code_client'=>$client->getCode(),
                    'rs'=>$client->getRsClient(),
                    'sigle'=>$client->getSigle(),
                    'type_client'=>$client->getTypeClient()? $client->getTypeClient()->getId(): "0",
                    'adresse'=>$client->getAdresse(),
                    'bp'=>$client->getBp(),
                    'email'=>$client->getEmail(),
                    'mobile'=>$client->getMobile(),
                    'tel'=>$client->getTel(),
                    'personne_ressource'=>$client->getPersonneRessource(),
                    'rccim'=>$client->getRccim(),
                    'cc'=>$client->getCc(),
                    'pays'=>$client->getCodePays() ? $client->getCodePays()->getId(): 0,
                    'ville'=>$client->getVille(),
                );
            } else {
                $reponse = array(
                    'code'=>'error',
                    'msg'=>'Merci de sélectionner un client dans la liste !'
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

    #[Route('/getTypesClients', name: 'get_types_clients')]
    public function get_types_clients(): Response
        {
            $reponse = array();
            try {
                $types_clients = $this->registry->getRepository(TypeClient::class)->findAll();
                $data = array();
                foreach ($types_clients as $t_clt){
                    $data[] = array(
                        'id'=>$t_clt->getId(),
                        'libelle'=>$t_clt->getLibelle()
                    );
                }
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

    #[Route('/saveClient', name: 'client_save')]
    public function client_save(Request $request): Response
    {
        $reponse = [];
        try {
            $id_client = $request->request->get('id_client');
            $rs_client = $request->request->get('rs_client');
            $sigle = $request->request->get('sigle');
            $email = $request->request->get('email');
            $adresse = $request->request->get('adresse');
            $bp= $request->request->get('bp');
            $tel = $request->request->get('tel');
            $mobile = $request->request->get('mobile');
            $cc = $request->request->get('cc');
            $rccim = $request->request->get('rccim');
            $personne_ressource = $request->request->get('personne_ressource');
            $type_client = (int) $request->request->get('type_client');
            $id_pays = (int) $request->request->get('pays');
            $id_ville = (int) $request->request->get('ville');

            $t_client = $this->registry->getRepository(TypeClient::class)->find($type_client);
            $pays = $this->registry->getRepository(Pays::class)->find($id_pays);
            $ville = $this->registry->getRepository(TypeClient::class)->find($id_ville);
            //dd($request);
            if (!$sigle || !$rs_client || !$mobile || !$personne_ressource || !$t_client|| !$pays) {
                $reponse = ['code' => 0, 'msg' => 'Merci de saisir tous les champs obligatoires !'];
            } else {
                $client = $this->registry->getRepository(ClientEcbi::class)->find($id_client);
                $isNew = false;

                if (!$client) {
                    $client = new ClientEcbi();
                    $client->setCode($this->outils->generateClientCode()); // code uniquement à la création
                    $client->setCreatedAt(new \DateTimeImmutable());
                    $client->setCreatedBy($this->getUser() ?: "NC");
                    $isNew = true;
                }

                    $client->setRsClient(strtoupper($rs_client));
                    $client->setSigle(strtoupper($sigle));
                    $client->setEmail($email);
                    $client->setTypeClient($t_client);
                    $client->setAdresse($adresse);
                    $client->setBp(strtoupper($bp));
                    $client->setMobile($mobile);
                    $client->setTel($tel);
                    $client->setCc(strtoupper($cc));
                    $client->setRccim(strtoupper($rccim));
                    $client->setPersonneRessource(strtoupper($personne_ressource));
                    $client->setCodePays($pays);
                    if ($ville){ $client->setVille($ville->getId());}

                    $em = $this->registry->getManager();
                    $em->persist($client);
                    $em->flush();

                $reponse = [
                    'code' => 1,
                    'msg' => $isNew ? 'Client créé avec succès !' : 'Client mis à jour avec succès !'
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est produite !<br>' . $throwable->getMessage()];
        }

        return new JsonResponse($reponse);
    }
}
