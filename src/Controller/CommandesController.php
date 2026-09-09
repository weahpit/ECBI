<?php

namespace App\Controller;

use App\Entity\ClientEcbi;
use App\Entity\Commande;
use App\Entity\FichierCommande;
use App\Entity\LigneProforma;
use App\Entity\Proforma;
use App\Services\NotificationService;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandesController extends AbstractController
{

    public function __construct(private ManagerRegistry $registry, private Outils $outils)
    {
    }

    #[Route('/NewOrder', name: 'app_commandes')]
    public function index(): Response
    {
        return $this->render('commandes/index.html.twig', [
            'numero_commande' => $this->outils->generateNumeroCommande(),
        ]);
    }
    #[Route('/OrdersList', name: 'liste_commandes')]
        public function liste_commandes(): Response
        {
            return $this->render('commandes/commandes.html.twig');
        }
    #[Route('/getCommandes', name: 'get_commandes')]
    public function get_commandes(): Response
    {
        $reponse = array();
        try {
            $commandes = $this->registry->getRepository(Commande::class)->findAll();
            $data = array();
            foreach ($commandes as $commande){
                $data[] = array(
                    'date_commande'=>$commande->getDateCommande()->format("d/m/Y"),
                    'id'=>$commande->getId(),
                    'numero_commande'=>$commande->getNumeroCommande(),
                    'client'=>$commande->getCodeClient() ? $commande->getCodeClient()->getSigle() : "-",
                    'montant'=>$commande->getCodeProforma() ? $commande->getCodeProforma()->getNetAPayer() : 0
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
    #[Route('/saveCommande', name: 'upload_multiple')]
    public function uploadMultiple(Request $request): Response
    {
        if(!$this->getUser()){return $this->redirectToRoute("app_login");}
       try {
            $files = $request->files->get('files'); // tableau de fichiers
            $id_client = $request->request->get('client');
            $id_proforma = $request->request->get('proforma');
            $dateCommande = $request->request->get('dateCommande');

            $client = $this->registry->getRepository(ClientEcbi::class)->find($id_client);
            $proforma = $this->registry->getRepository(Proforma::class)->find($id_proforma);

            if (!$files && !$client && !$proforma) {
                return new Response('Client ou proforma non sélectionné !', 500);
            }elseif (!$files && !$client && !$proforma) {
                return new Response('Aucun fichier reçu', 400);
            }
            $commande = new Commande();
            $commande->setNumeroCommande($this->outils->generateNumeroCommande());
            $date_commande = new \DateTime($dateCommande);
            $commande->setDateCommande($date_commande);
            $commande->setCodeClient($client);
            $commande->setCodeProforma($proforma);
            $commande->setCreatedAt(new \DateTimeImmutable());
            $commande->setCreatedBy($this->getUser());
            $commande->setStatut("NON LIVRE");
            $commande->setEtat(false);

            $this->registry->getManager()->persist($commande);

            $uploaded = [];
            foreach ($files as $file) {

                if ($file) {
                    $fichier = new FichierCommande();
                    $filename = uniqid().'.'.$file->guessExtension();
                    $fichier->setFilename($filename);
                    $fichier->setCodeCommande($commande);
                    $this->registry->getManager()->persist($fichier);

                    $file->move($this->getParameter('bc_directory'), $filename);
                    $uploaded[] = $filename;
                }
            }
            $this->registry->getManager()->flush();

        return $this->json([
                'status' => 'ok',
                'files' => $uploaded,
                'nc'=>$this->outils->generateNumeroCommande()
            ]);
        }catch (\Throwable $throwable){
            return $this->json([
                'status' => 'Error'
            ]);
        }
    }

    #[Route('/getSingleCommande/{id_commande}', name: 'get_single_commande')]
    public function get_single_commande(int $id_commande): Response
    {
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        $reponse = array();
        $data_produit = array();
        $data_fichiers= array();
        try {
            $commande = $this->registry->getRepository(Commande::class)->find($id_commande);

            if ($commande){
                if ($commande->getCodeProforma()){
                    $proforma = $commande->getCodeProforma();
                    $fichiers = $commande->getFichierCommandes();
                    $lignesProduits = $this->registry->getRepository(LigneProforma::class)->findBy(['code_proforma'=>$proforma]);
                        foreach($lignesProduits as $ligne){
                            $data_produit[] = array(
                                'qte'=>$ligne->getQte(),
                                'pu'=>$ligne->getPrix(),
                                'produit'=>$ligne->getCodeProduit() ? $ligne->getCodeProduit()->getId() : 0,
                                'libelle_produit'=>$ligne->getCodeProduit() ? $ligne->getCodeProduit()->getLibelle() : "",
                                'net'=>$ligne->getTotal(),
                            );
                        }
                        foreach($fichiers as $fichier){
                            $data_fichiers[] = array(
                                'fichier'=>$fichier->getFilename()
                            );
                        }
                        $reponse = array(
                            'code'=>1,
                            'msg'=>'Success',
                            'numero_commande'=>$commande->getNumeroCommande(),
                            'date_commande'=>$commande->getDateCommande()->format('d/m/Y'),
                            'client'=>$commande->getCodeClient() ? $commande->getCodeClient()->getId() : 0,
                            'total_ht'=>$proforma->getTotalHt()? :"",
                            'remise'=>$proforma->getRemise()? :"",
                            'tva'=>$proforma->getTva()? :"",
                            'total_ttc'=>$proforma->getNetAPayer()? :"",
                            'statut'=>$commande->getStatut()? :"",
                            'created_at'=>$commande->getCreatedAt()->format('d/m/Y')? :"",
                            'created_by'=>$commande->getCreatedBy()? :"",
                            'conditions'=>$commande->getConditions()? :"" ,
                            'data'=>$data_produit,
                            'data_fichiers'=>$data_fichiers,
                            'etat'=>$commande->isEtat()? :"",
                            'user'=>$commande->getCreatedBy()? :"",
                            'quotation'=>$commande->getCodeProforma()->getNumeroProforma()? :"",
                            'date_quotation'=>$commande->getCodeProforma()->getDateProforma() ? $commande->getCodeProforma()->getDateProforma()->format("d/m/Y") : "",
                        );
                }
            } else {
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Merci de sélectionner une commande dans la liste !'
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
}
