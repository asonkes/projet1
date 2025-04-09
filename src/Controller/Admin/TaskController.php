<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TaskController extends AbstractController
{
    #[Route('/admin/task', name: 'admin_task')]
    public function index(): Response
    {
        return $this->render('admin/task/index.html.twig');
    }
}
