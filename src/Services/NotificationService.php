<?php

namespace App\Services;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function notify(User $destinataire, string $sujet, string $message,string $target): void
    {
        $notif = new Notification();
        $notif->setDestinataire($destinataire);
        $notif->setLibelle($sujet);
        $notif->setDescription($message);
        $notif->setLu(false);
        $notif->setTarget($target);

        $notif->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($notif);
        $this->em->flush();
    }
}
