<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepository): Response
    {
        // On récupère l'utilisateur
        $user = $this->getUser();

        // Si pas d'utilisateur, redirection vers la page connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $project = $projectRepository->findBy(['user' => $user]);

        return $this->render('home/index.html.twig', [
            'project' => $project,
        ]);
    }
}
