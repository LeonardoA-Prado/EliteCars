<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/politicas', name: 'app_politicas')]
    public function politicas(): Response
    {
        return $this->render('privacidad.html.twig');
    }
}
