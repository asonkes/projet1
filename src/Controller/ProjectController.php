<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project')]
    public function index(): Response
    {
        // On récupère l'utilisateur
        $user = $this->getUser();

        // Si pas d'utilisateur, redirigé vers la page connexion
        if (!$user) {
            return $this->redirectToroute('app_login');
        }

        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }
}
