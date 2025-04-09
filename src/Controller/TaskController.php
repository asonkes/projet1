<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        // On récupère l'utilisateur
        $user = $this->getUser();

        // Si pas d'utilisateur, redirection vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
}
