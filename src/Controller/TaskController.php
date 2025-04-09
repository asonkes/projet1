<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    //#[Route('/task/{id}', name: 'app_task')]
    public function index(ProjectRepository $projectRepository): Response
    {
        // On récupère l'utilisateur
        $user = $this->getUser();

        // Si pas d'utilisateur, redirection vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // On récupère le projet de l'utilisateur par ID
        //$project = $projectRepository->find($id);

        // On récupère le projet de l'utilisateur
        $project = $projectRepository->findBy(['user' => $user]);

        // On récupère les tâches des projets
        $tasks = [];

        // On boucle pour sur chaque projet de l'utilisateur
        foreach ($project as $project) {

            // Ici, on boucle sur le projet sélectionné (on fait une sélection sur le projet existant)
            $projectTasks = $project->getProjectTasks();

            // Et ici, on va récupérer l'id des tâches correspondantes en fonction du projet sélectionné plus haut
            foreach ($projectTasks as $projectTask) {
                $tasks[] = $projectTask->getTask();
            }
        }

        return $this->render('task/index.html.twig', [
            'project' => $project,
            'tasks' => $tasks
        ]);
    }
}
