<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin-board", name="admin")
     */
    public function index(): Response
    {
    	return $this->render('app/admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}


