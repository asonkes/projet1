<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProjectController extends AbstractController
{

    #[Route('admin/project', name: 'admin_project')]
    public function index(): Response
    {
        return $this->render('admin/project/index.html.twig');
    }
}
