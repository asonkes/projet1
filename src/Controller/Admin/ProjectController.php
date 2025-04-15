<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin/project', name: 'admin_project_')]
final class ProjectController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/project/index.html.twig');
    }

    #[Route('/ajout', name: 'add')]
    public function add(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/project/index.html.twig');
    }

    #[Route('/modifier/{id}', name: 'edit')]
    public function edit(Project $project): Response
    {
        // On vérifie si l'utilisateur peut éditer avec le "voter"
        $this->denyAccessUnlessGranted('PROJECT_EDIT', $project);

        return $this->render('admin/project/index.html.twig');
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Project $project): Response
    {
        // On vérifie si l'utilisateur peut supprimer avec le "voter"
        $this->denyAccessUnlessGranted('PROJECT_DELETE', $project);

        return $this->render('admin/project/index.html.twig');
    }
}
