<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepository, Request $request): Response
    {
        // On récupère l'utilisateur
        $user = $this->getUser();

        // Si pas d'utilisateur, redirection vers la page connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // On va chercher le numéro de page dans l'url
        // On met la requête -> $request
        // Puis la requête du "repository" -> query
        // et s'il y a pas de page, par défaut = 1
        $page = $request->query->getInt('page', 1);


        // On va chercher la liste des projets des utlisateurs
        // $project = $projectRepository->findBy(['user' => $user]);
        $project = $projectRepository->findProjectPaginated($page, $user, 2); // 2 est la limit

        return $this->render('home/index.html.twig', [
            'project' => $project,
        ]);
    }
}
