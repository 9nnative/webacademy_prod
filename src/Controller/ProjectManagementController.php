<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectManagementController extends AbstractController
{
    #[Route('/project_management_test', name: 'app_project_management')]
    public function index(): Response
    {
        return $this->render('project_management/index.html.twig', [
            'controller_name' => 'ProjectManagementController',
        ]);
    }
}
