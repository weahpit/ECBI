<?php

namespace App\Services;
use App\Entity\Commande;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class Outils
{
    public function __construct(private MailerInterface $mailer, private ManagerRegistry $em)
    {
    }

    function generateClientCode($length = 6): string {
        // Caractères autorisés
        $chars = '0123456789';
        $charsLength = strlen($chars);
        $code = '';

        // Génération sécurisée
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, $charsLength - 1)];
        }

        return strtoupper($code);
    }
    function generateNumeroCommande(): string {
        $code = '';
        $annee = new \DateTime();
        $nbCommande = $this->em->getRepository(Commande::class)->count([]) + 1;
        if ($nbCommande){
            $taille = strlen($nbCommande);
           if ($taille == 1){
               $code = '00' . $nbCommande;
           } elseif ($taille == 2){
               $code = '0' . $nbCommande;
           } else {
               $code = $nbCommande;
           }
            return strtoupper($code . "-".$annee->format("Y"));
        } else {
            return strtoupper("Erreur!");
        }
    }
    function generateToken($length = 64): string {
        // Caractères autorisés
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz@!-*+';
        $charsLength = strlen($chars);
        $code = '';

        // Génération sécurisée
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, $charsLength - 1)];
        }

        return strtoupper($code);
    }

    function sendEmail($to, $subject, $content): void
    {

        $email = (new Email())
            ->from('infos@ecbi.ci')
            ->to($to)
            ->subject($subject)
            ->html($content);

            $this->mailer->send($email);

    }
}
