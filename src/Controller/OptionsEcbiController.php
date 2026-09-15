<?php

namespace App\Controller;

use App\Entity\OptionsEcbi;
use App\Services\Outils;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
final class OptionsEcbiController extends AbstractController
{
    public function __construct(private ManagerRegistry $registry, private Outils $outils, private MailerInterface $mailer,)
    {
    }

    #[Route('/getOptionsEcbi', name: 'get_options')]
    public function get_options(): Response
    {
        $reponse = array();
        try {
            $options = $this->registry->getRepository(OptionsEcbi::class)->findAll();
            $data = array();
            foreach ($options as $optionEcbi){
                $data[] = array(
                    'id'=>$optionEcbi->getId(),
                    'libelle'=>$optionEcbi->getLibelle(),
                    'classname'=>$optionEcbi->getClassname(),
                    'valeur'=>$optionEcbi->getValue() ?$optionEcbi->getValue() : ""
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

    #[Route('/saveOption/{id_option}/{valeur}', name: 'save_options')]
    public function save_options(int $id_option, string $valeur): Response
    {
        $reponse = array();
        try {
            $optionEcbi = $this->registry->getRepository(OptionsEcbi::class)->find($id_option);
            $data = array();
            if ($optionEcbi){
                $optionEcbi->setValue($valeur);
                $this->registry->getManager()->persist($optionEcbi);
                $this->registry->getManager()->flush();
                $reponse = array(
                    'code'=>'success',
                    'msg'=>'Option mise à jour avec succès',
                    'id'=>$optionEcbi->getId(),
                    'libelle'=>$optionEcbi->getLibelle(),
                    'classname'=>$optionEcbi->getClassname(),
                    'valeur'=>$optionEcbi->getValue()
                );
            } else {
                $reponse = array(
                    'code'=>'error',
                    'msg'=>'Aucune option sélctionnée',
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

    #[Route('/testMail', name: 'test_mail')]
    public function test_mail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('infos@ecbi.ci')
            ->to('aziz.ndia@citrac.ci2')
            ->subject('Test d’envoi via Infomaniak')
            ->text('Bonjour, ceci est un email envoyé avec Symfony et Infomaniak.')
            ->html('<p><b>Bonjour</b>, ceci est un email envoyé avec <i>Symfony</i> et Infomaniak.</p>');

        $mailer->send($email);

        return new Response('Email envoyé avec succès via Infomaniak !');
    }
}
