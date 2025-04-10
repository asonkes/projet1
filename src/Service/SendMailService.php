<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private $mailer;

    // On fait le constructeur avec le "mailerInterface" (interface qui permet d'envoyer des mails)
    // Sans cette interface, le php et donc l'envoi d'emails ne fonctionne pas
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    // Ici c'est une méthode car on se trouve dans une classe
    // Et cette méthode va prendre plusieurs paramètres
    // Mais ne renverra rien (: void)
    public function send(
        // châine de caractère (string) => de qui ça vient
        string $from,
        // châine de caractère (string) => destinataire du mail
        string $to,
        // châine de caractère (string) => sujet du mail
        string $subject,
        // châine de caractère (string) => twig que l'on va aller chercher pour le mail
        string $template,
        // Tableau qui va contenir les différentes variables que l'on va utiliser au niveau de notre mail
        array $context
    ): void {
        // On va créé le mail (composant qui va permettre de créer des mails)
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            // On préfixe le template, il suffira après de mettre le nom du template que l'on a besoin
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        // On envoie le mail
        $this->mailer->send($email);
    }
}
