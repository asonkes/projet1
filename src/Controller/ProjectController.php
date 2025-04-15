<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/project', name: 'app_project_')]
final class ProjectController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

    #[Route('/ajout', name: 'add')]
    // EntityManagerInterface ==> permet le stockage des informations en base de données
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On créé un nouveau projet
        $project = new Project();

        // On crée le formulaire 
        $projectForm = $this->createForm(ProjectFormType::class, $project);

        // On traite la requête du formulaire
        $projectForm->handleRequest($request);
        // On vérifie si le formulaire est soumis et valide
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {

            // On vérifie l'utilisateur connecté
            $user = $this->getUser();

            // On associe l'utilisateur connecté au nouveau projet crée (au sinon va choisir l'utilisateur par défaut)
            $project->setUser($user);

            $entityManager->persist($project);

            $entityManager->flush();

            $this->addFlash('success', 'Votre projet à bien été ajouté à votre liste !');
        }

        return $this->render('project/add.html.twig', [
            'projectForm' => $projectForm->createView()
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Project $project, Request $request, EntityManagerInterface $entityManager)
    {
        // On crée le formulaire 
        $projectForm = $this->createForm(ProjectFormType::class, $project);

        // On traite la requête du formulaire
        $projectForm->handleRequest($request);
        // On vérifie si le formulaire est soumis et valide
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {

            $entityManager->persist($project);

            $entityManager->flush();

            $this->addFlash('success', 'Votre projet à été modifié avec succès !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('project/edit.html.twig', [
            'projectForm' => $projectForm->createView()
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete() {}
}
