<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController
{
    #[Route('/task/{id}', name: 'app_task')]
    public function index(ProjectRepository $projectRepository, $id): Response
    {
        // On récupère l'utilisateur
        $user = $this->getUser();

        // Si pas d'utilisateur, redirection vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // On récupère le projet de l'utilisateur par ID du projet qui est transmis à l'url
        $project = $projectRepository->find($id);

        // On récupère les tâches des projets
        $tasks = [];

        // Ici, on boucle sur le projet sélectionné (on fait une sélection sur le projet existant)
        $projectTasks = $project->getProjectTasks();

        // Et ici, on va récupérer l'id des tâches correspondantes en fonction du projet sélectionné plus haut
        foreach ($projectTasks as $projectTask) {
            $tasks[] = $projectTask->getTask();
        }

        return $this->render('task/index.html.twig', [
            'project' => $project,
            'tasks' => $tasks
        ]);
    }
}
