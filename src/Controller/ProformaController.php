<?php

namespace App\Controller;

use App\Entity\ClientEcbi;
use App\Entity\GrilleTarif;
use App\Entity\InfosSociete;
use App\Entity\LigneProforma;
use App\Entity\OptionsEcbi;
use App\Entity\Produit;
use App\Entity\Proforma;
use App\Entity\ProgrammationAlerte;
use App\Entity\TarifProduitGrille;
use App\Entity\Ville;
use App\Services\NotificationService;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use IntlDateFormatter;
use Knp\Snappy\Pdf;
use phpDocumentor\Reflection\Types\This;
use setasign\Fpdi\Fpdi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\Translation\t;

final class ProformaController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $registry,
        private Outils $outils,
        private Pdf $pdf,
        private NotificationService $notif
    )
    {
    }

    #[Route('/proforma', name: 'app_proforma')]
    public function index(): Response
    {
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        $taux_tva = $this->registry->getRepository(OptionsEcbi::class)->findOneBy(['classname'=>'tva']);
        $validite = $this->registry->getRepository(OptionsEcbi::class)->findOneBy(['classname'=>'validite_proforma']);

        return $this->render('proforma/index.html.twig', [
            'tva'=>$taux_tva? $taux_tva->getValue() : 0,
            'validite'=>$validite? $validite->getValue() : 0,
        ]);
    }

    #[Route('/getProformas', name: 'get_proformas')]
    public function get_proformas(): Response
    {
        $reponse = array();
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        try {
            $proformas = $this->registry->getRepository(Proforma::class)->findAll();
            $data = array();
            foreach ($proformas as $proforma){
                $data[] = array(
                    'id'=>$proforma->getId(),
                    'ref_Proforma'=>$proforma->getNumeroProforma(),
                    'dateProforma'=>$proforma->getDateProforma()->format('d/m/Y'),
                    'client'=>$proforma->getCodeClient()? $proforma->getCodeClient()->getSigle() : "-",
                    'montant'=>$proforma->getNetAPayer(),
                    'etat'=>$proforma->isEtat() ? 1: 0
                );
            }
            rsort($data);
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


    #[Route('/getListeNonValidees', name: 'getListeNonValidees')]
    public function getListeNonValidees(): Response
    {
        $reponse = array();
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        try {
            $proformas = $this->registry->getRepository(Proforma::class)->findBy(['etat'=>false]);
            $data = array();
            $i=0;
            foreach ($proformas as $proforma){
                $i++;
                $data[] = array(
                    'id'=>$proforma->getId(),
                    'ref_Proforma'=>$proforma->getNumeroProforma(),
                    'dateProforma'=>$proforma->getDateProforma()->format('d/m/Y'),
                    'client'=>$proforma->getCodeClient()? $proforma->getCodeClient()->getSigle() : "-",
                    'montant'=>$proforma->getNetAPayer(),
                    'etat'=>$proforma->isEtat() ? 1: 0
                );
            }
            rsort($data);
            $reponse = array(
                'code'=>1,
                'msg'=>'Success',
                'nb'=>$i,
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
    #[Route('/getProformasByClient/{id_client}', name: 'get_proformas_by_client')]
    public function get_proformas_by_client(int $id_client): Response
    {
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        $reponse = array();
        try {
            $client = $this->registry->getRepository(ClientEcbi::class)->find($id_client);
            $data = array();
            if ($client){
                $proformas = $this->registry->getRepository(Proforma::class)->findBy(['code_client'=>$client]);
                foreach ($proformas as $proforma){
                    $data[] = array(
                        'id'=>$proforma->getId(),
                        'ref_Proforma'=>$proforma->getNumeroProforma(),
                        'dateProforma'=>$proforma->getDateProforma()->format('d/m/Y'),
                        'client'=>$proforma->getCodeClient()? $proforma->getCodeClient()->getSigle() : "-",
                        'montant'=>$proforma->getNetAPayer(),
                        'etat'=>$proforma->isEtat()
                    );
                }

                $reponse = array(
                    'code'=>1,
                    'msg'=>'Success',
                    'data'=>$data
                );
            } else {
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Erreur Client !',
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

    #[Route('/saveProforma', name: 'proforma_save')]
    public function proforma_save(Request $request): Response
    {
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        $reponse = [];
        try {
            $id_proforma = $request->request->get('id_proforma');
            $id_client = $request->request->get('client');
            $id_grille = $request->request->get('grille');
            $date_proforma = $request->request->get('date_proforma');
            $validiteProforma = $request->request->get('validiteProforma');
            $lignes= $request->request->get('lignes');
            $total_ht = $request->request->get('total_ht');
            $tva = $request->request->get('tva');
            $taux_remise = $request->request->get('taux_remise');
            $total_ttc = $request->request->get('total_ttc');
            $remise = $request->request->get('remise');
            $en_lettre = $request->request->get('en_lettre');

            $lignes_proforma = json_decode($lignes);

            $client = $this->registry->getRepository(ClientEcbi::class)->find($id_client);
            $grille = $this->registry->getRepository(GrilleTarif::class)->find($id_grille);


            //dd($lignes_proforma);
            if ((float) $total_ht <= 0 ) {
                $reponse = ['code' => 0, 'msg' => 'Sélectionnez au moins un produit et sa quantité  !'];
            }elseif (!$client && !$grille ) {
                $reponse = ['code' => 0, 'msg' => 'Merci de saisir le client et la grille tarifaire !'];
            } else {

                $proforma = new Proforma();
                $em = $this->registry->getManager();
                $dateproforma = new \DateTime($date_proforma);
                $aujourdhui = new \DateTime();
                $proforma->setDateProforma($dateproforma);
                $proforma->setNumeroProforma($this->outils->generateClientCode());
                $proforma->setCodeClient($client);
                $proforma->setTotalHt($total_ht);
                $proforma->setTva($tva);
                $proforma->setTauxRemise($taux_remise);
                $proforma->setRemise($remise);
                $proforma->setNetAPayer($total_ttc);
                $proforma->setCreatedAt(new \DateTimeImmutable());
                if ($this->getUser()) {$proforma->setCreatedBy($this->getUser());}
                $proforma->setDateLivraison($aujourdhui->modify('+3 days'));
                $proforma->setIdSys($this->outils->generateToken());
                $proforma->setMontantLettre($en_lettre);
                $proforma->setStatut("VALIDATION");
                $proforma->setEtat(false);
                $proforma->setDureeValidite($validiteProforma);
                // Enreegistrement des lignes

                foreach ($lignes_proforma as $ligne){
                    $ligneProforma = new LigneProforma();
                    //dd( $ligne->produit);
                    $produit_tarif = $this->registry->getRepository(TarifProduitGrille::class)->find((int) $ligne->produit);
                    if ($produit_tarif) {
                        $ligneProforma->setCodeProduit($produit_tarif->getCodeProduit());
                        $ligneProforma->setCodeGrille($produit_tarif->getCodeGrille());
                        $ligneProforma->setCodeProforma($proforma);
                        $ligneProforma->setQte((int) $ligne->quantite);
                        $ligneProforma->setPrix((float) $ligne->prixUnitaire);
                        $ligneProforma->setTotal((float) $ligne->total);
                        $em->persist($ligneProforma);
                    }
                }
                //dd($proforma);
                $em->persist($proforma);

                $em->flush();

                $qrCode = new QrCode(
                    data: $proforma->getIdSys()
                );

                $writer = new PngWriter();

                $result = $writer->write($qrCode);

                $directory = $this->getParameter('kernel.project_dir').'/public/docs/QR/Proforma/';

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $result->saveToFile($directory.'pf'.$proforma->getId().'.png');

                $programmation = $this->registry->getRepository(ProgrammationAlerte::class)->findOneBy(['libelle_alerte'=>'PROFORMA']);
                if ($programmation){
                    $alertes = $programmation->getAlertesUsers();
                    foreach ($alertes as $alerte){
                        if ($alerte->getCodeUser()){
                            $this->notif->notify(
                                $alerte->getCodeUser(),
                                'Nouvelle Proforma | Réf : ' . $proforma->getNumeroProforma(). '<br>'. $proforma->getCodeClient()->getSigle(),
                                "L'agent " . $this->getUser() . " vient de générer une proforma à valider",
                                $this->generateUrl("app_validate_doc", ['type_doc'=>1, 'token'=>$proforma->getIdSys()])
                            );
                        }
                    }
                }

                $reponse = [
                    'code' => 1,
                    'msg' =>  'Proforma créée avec succès !',
                    'id'=>$proforma->getId()
                ];
            }
        } catch (\Throwable $throwable) {
            $reponse = ['code' => 0, 'msg' => 'Une erreur s\'est produite !<br>' . $throwable->getMessage()];
        }

        return new JsonResponse($reponse);
    }

    #[Route('/getSingleProforma/{id_proforma}', name: 'get_single_proforma')]
    public function get_single_proforma(int $id_proforma): Response
    {
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        $reponse = array();
        $data_produit = array();
        try {
            $proforma = $this->registry->getRepository(Proforma::class)->find($id_proforma);
            $taux_tva = $this->registry->getRepository(OptionsEcbi::class)->findOneBy(['classname'=>'tva']);
            if ($taux_tva){$taux_remise = $taux_tva->getValue();} else{ $taux_remise = 0;}


            if ($proforma){

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
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Success',
                    'id'=>$proforma->getId(),
                    'numero_proforma'=>$proforma->getNumeroProforma(),
                    'date_proforma'=>$proforma->getDateProforma()->format('d/m/Y'),
                    'description_produit'=>$proforma->getCodeClient() ? $proforma->getCodeClient()->getId() : 0,
                    'total_ht'=>$proforma->getTotalHt(),
                    'remise'=>$proforma->getRemise(),
                    'taux_remise'=>$taux_remise,
                    'tva'=>$proforma->getTva(),
                    'total_ttc'=>$proforma->getNetAPayer(),
                    'statut'=>$proforma->getStatut(),
                    'created_at'=>$proforma->getCreatedAt()->format('d/m/Y'),
                    'created_by'=>$proforma->getCreatedBy(),
                    'conditions'=>$proforma->getConditions(),
                    'data'=>$data_produit,
                    'etat'=>$proforma->isEtat(),
                    'user'=>$proforma->getCreatedBy(),
                    'rs_client'=>$proforma->getCodeClient()?$proforma->getCodeClient()->getRsClient() : "" ,
                    'sigle'=>$proforma->getCodeClient()?$proforma->getCodeClient()->getSigle() : "",
                    'duree_validite'=>$proforma->getDureeValidite()
                );
            } else {
                $reponse = array(
                    'code'=>0,
                    'msg'=>'Merci de sélectionner une proforma dans la liste !'
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


    #[Route('/imprimerProforma/{id_proforma}', name: 'imprimer_proforma')]
    public function imprimer_proforma(int $id_proforma): Response
    {
        $proforma = $this->registry->getRepository(Proforma::class)->find($id_proforma);
        $societe = $this->registry->getRepository(InfosSociete::class)->findOneBy([]);
        $optionsEcbi = $this->registry->getRepository(OptionsEcbi::class)->findOneBy(['classname'=>'tva']);
        if ($proforma){
            $pdf = new Fpdi();
            setlocale(LC_TIME, 'fr_FR.UTF-8');
            $formatter = new IntlDateFormatter(
                'fr_FR',
                IntlDateFormatter::LONG,
                IntlDateFormatter::NONE
            );

            $pdf->AddPage();

            $pdf->setSourceFile(
                $this->getParameter('kernel.project_dir')
                . '/public/docs/modeles/proforma.pdf'
            );

            $template = $pdf->importPage(1);
            $pdf->useTemplate($template);

            // Écriture sur le modèle


            $pdf->SetFont('Arial', '', 10);

            $pdf->SetXY(125, 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(0, 5, mb_convert_encoding('éditée par '. $proforma->getCreatedBy() . ' - ' . $proforma->getCreatedAt()->format('d/m/Y h:i:s'), 'ISO-8859-1', 'UTF-8'));


// FACTURE PRO FORMA
            $pdf->SetXY(10, 52);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 5, mb_convert_encoding('FACTURE PROFORMA N° '. $proforma->getNumeroProforma(), 'ISO-8859-1', 'UTF-8'));

            $pdf->SetFont('Arial', '', 9);

// Régime
            $pdf->SetXY(10, 57);
            $pdf->Cell(0, 5, mb_convert_encoding('Régime d\'Imposition : '. $societe->getTypeImposition(), 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(10, 62);
            $pdf->Cell(0, 5, mb_convert_encoding('Centre des Impôts : '.$societe->getCentreImpots(), 'ISO-8859-1', 'UTF-8'));

// Date

            $pdf->SetXY(10, 70);
            $pdf->Cell(40, 5, 'Date :');
            $pdf->Cell(80, 5, mb_convert_encoding(ucfirst($formatter->format($proforma->getDateProforma())), 'ISO-8859-1', 'UTF-8'));

// Références
            $pdf->SetXY(10, 75);
            $pdf->Cell(40, 5, mb_convert_encoding('Nos références', 'ISO-8859-1', 'UTF-8'));
            $pdf->Cell(80, 5, mb_convert_encoding($proforma->getCodeClient()->getRsClient(), 'ISO-8859-1', 'UTF-8'));


            $directory = $this->getParameter('kernel.project_dir').'/public/docs/QR/Proforma/pf';

            $pdf->Image($directory.$proforma->getId().'.png', 170, 50, 30, 30);

            $pdf->SetXY(10, 80);
            $pdf->Cell(40, 5, 'Contact');
            $pdf->Cell(80, 5, $proforma->getCodeClient()->getMobile());

            $pdf->SetXY(10, 85);
            $pdf->Cell(40, 5, mb_convert_encoding('Vos références', 'ISO-8859-1', 'UTF-8'));
            $pdf->Cell(80, 5, $proforma->getCreatedBy());

            $pdf->SetXY(10, 90);
            $pdf->Cell(40, 5, 'Contact');
            $pdf->Cell(80, 5, '+225 07 07 70 74 71');

// Client
            $pdf->SetXY(10, 95);
            $pdf->Cell(40, 5, 'Client');
            $pdf->Cell(80, 5, mb_convert_encoding($proforma->getCodeClient()->getSigle(), 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(10, 100);
            $pdf->Cell(40, 5, 'CC');
            $pdf->Cell(80, 5, mb_convert_encoding($proforma->getCodeClient()->getCc(), 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(10, 105);
            $pdf->Cell(40, 5, mb_convert_encoding('Téléphone', 'ISO-8859-1', 'UTF-8'));
            $pdf->Cell(80, 5, mb_convert_encoding($proforma->getCodeClient()->getTel(), 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(10, 110);
            $pdf->Cell(40, 5, 'Adresse');
            $pdf->Cell(80, 5, mb_convert_encoding($proforma->getCodeClient()->getBp(), 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(50, 115);
            $pdf->Cell(80, 5, mb_convert_encoding($proforma->getCodeClient()->getVille() ? $this->registry->getRepository(Ville::class)->find((int)$proforma->getCodeClient()->getVille())->getLibelle() : "", 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(50, 120);
            $pdf->Cell(80, 5, mb_convert_encoding($proforma->getCodeClient()->getCodePays() ? $proforma->getCodeClient()->getCodePays()->getLibelle() : "", 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(10, 125);
            $pdf->Cell(40, 5, 'E-mail');
            $pdf->Cell(80, 5, mb_convert_encoding($proforma->getCodeClient()->getEmail(), 'ISO-8859-1', 'UTF-8'));



// En-têtes du tableau
            $pdf->SetXY(10, 135);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(227, 227, 227);
            $pdf->Cell(15, 8, mb_convert_encoding('N°', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
            $pdf->Cell(80, 8, mb_convert_encoding('Désignation', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
            $pdf->Cell(25, 8, mb_convert_encoding('Qté', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
            $pdf->Cell(35, 8, 'P.U', 1, 0, 'C', true);
            $pdf->Cell(35, 8, 'Montant', 1, 1, 'C', true);

// Corps du tableau
            //$pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('Arial', '', 9);
            $lignes = $this->registry->getRepository(LigneProforma::class)->findBy(['code_proforma'=>$proforma]);

            $index = 0;

            foreach ($lignes as $ligne) {
                $index++;
                $pdf->Cell(15, 5, $index + 1, 1, 0, 'C');
                $pdf->Cell(80, 5, mb_convert_encoding($ligne->getCodeProduit()->getLibelle(), 'ISO-8859-1', 'UTF-8'), 1);
                $pdf->Cell(25, 5, $ligne->getQte(), 1, 0, 'C');
                $pdf->Cell(35, 5, number_format($ligne->getPrix(), 0, ',', ' '), 1, 0, 'C');
                $pdf->Cell(35, 5, number_format($ligne->getTotal(), 0, ',', ' '), 1, 1, 'C');
            }

// Ligne Total
            $pdf->SetFont('Arial', 'B', 9);

            $pdf->Cell(155, 6, mb_convert_encoding('Total HT', 'ISO-8859-1', 'UTF-8'), 0, 0, 'R');
            $pdf->Cell(35, 6, number_format($proforma->getTotalHt(), 0, ',', ' '), 1, 1, 'R');

            $pdf->Cell(155, 6, mb_convert_encoding('Remise ('. $proforma->getTauxRemise() . '%)', 'ISO-8859-1', 'UTF-8'), 0, 0, 'R');
            $pdf->Cell(35, 6, number_format($proforma->getRemise(), 0, ',', ' '), 1, 1, 'R');


            $pdf->Cell(155, 6, mb_convert_encoding('TVA ('. $optionsEcbi->getValue() . '%)', 'ISO-8859-1', 'UTF-8'), 0, 0, 'R');
            $pdf->Cell(35, 6, number_format($proforma->getTva(), 0, ',', ' '), 1, 1, 'R');


            $pdf->Cell(155, 8, mb_convert_encoding('Net à Payer TTC', 'ISO-8859-1', 'UTF-8'), 0, 0, 'R');
            $pdf->Cell(35, 8, number_format($proforma->getNetAPayer(), 0, ',', ' '). " F CFA", 1, 1, 'R',true);


// Livraison
            $pdf->SetXY(10, 195);
            $pdf->Cell(40, 5, mb_convert_encoding('Validité :', 'ISO-8859-1', 'UTF-8'), 'ISO-8859-1', 'UTF-8');
            $pdf->SetXY(25, 195);
            $pdf->SetTextColor(253,0,0);
            $pdf->Cell(40, 5, mb_convert_encoding($proforma->getDureeValidite() . ' jours' , 'ISO-8859-1', 'UTF-8'), 'ISO-8859-1', 'UTF-8');
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial', '', 9);
// Montant en lettres
            $pdf->SetXY(10, 205);
            $pdf->MultiCell(
                170,
                5,
                mb_convert_encoding(
                    'Arrêtée la présente facture Pro Forma à ', 'ISO-8859-1', 'UTF-8')
            );
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(67, 205);
            $pdf->Cell(40, 5,  $proforma->getMontantLettre() . ' francs CFA');
            $pdf->SetFont('Arial', '', 9);
// Paiement
            $pdf->SetXY(10, 210);
            $pdf->Cell(0, 5, mb_convert_encoding('Mode de paiement : anticipé', 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(10, 215);
            $pdf->Cell(0, 5, mb_convert_encoding("Par chèque à l'ordre E.C.B.I.", 'ISO-8859-1', 'UTF-8'));

            $pdf->SetXY(10, 220);
            $pdf->Cell(
                120,
                5,
                mb_convert_encoding('En espèces prévoir les frais de timbres', 'ISO-8859-1', 'UTF-8')
            );
            $pdf->SetFont('Arial', 'B', 10);
// Signature
            $pdf->SetXY(145, 220);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(40, 5, mb_convert_encoding('Direction Commerciale', 'ISO-8859-1', 'UTF-8'));

            $pdf->Output();
        }
    }
    #[Route('/acceptProforma/{id_proforma}', name: 'accept_proforma')]
    public function accept_proforma(int $id_proforma): Response
    {
        if (!$this->getUser()){return $this->redirectToRoute("app_login");}
        $reponse = array();
        try {
            $proforma = $this->registry->getRepository(Proforma::class)->find($id_proforma);
            if ($proforma){
                $proforma->setStatut(strtoupper("Validé"));
                $proforma->setEtat(true);
                $this->registry->getManager()->persist($proforma);
                $this->registry->getManager()->flush();
                $reponse = array(
                    'code'=>1,
                    'msg'=>'Proforma validée avec succès !'
                );
            } else {
                $reponse = array(
                    'code'=>2,
                    'msg'=>'Cette proforma n\'existe pas !'
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
