<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/liste', name: 'app.home')]
    public function index(UserRepository $repository): Response
    {
        return $this->render('home/index.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }
}
