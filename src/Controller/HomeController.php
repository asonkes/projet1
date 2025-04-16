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

        // On va chercher le numéro de page dans l'url (comme /?page=2)
        // on fait passer la requete => query
        // getInt() ==>sécurise et convertit la valeur en entier
        // Si aucune page n'est définie dans l'URL, la valeur par défaut est '1'
        $page = $request->query->getInt('page', 1);


        // On va chercher la liste des projets des utlisateurs
        // $project = $projectRepository->findBy(['user' => $user]);

        // $page ==> pour savoir quels projets afficher
        // $user ==> pour ne récupérer que les projets de l'utilisateur connecté
        // 2 ==> nombre de projets par page
        $project = $projectRepository->findProjectPaginated($page, $user, 2); // 2 est la limit

        return $this->render('home/index.html.twig', [
            'project' => $project,
        ]);
    }
}
