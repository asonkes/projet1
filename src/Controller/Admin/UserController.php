<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'admin_user')]
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig');
    }
}
