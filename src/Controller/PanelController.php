<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\MarcasRepository;
use App\Repository\CombustibleRepository;

#[Route('/admin/panel')]
final class PanelController extends AbstractController
{
    #[Route('/', name: 'admin_panel_index', methods: ['GET'])]
    public function index(MarcasRepository $marcasRepository, CombustibleRepository $combustibleRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_coche_index');
        }
        
        return $this->render('admin/panel.html.twig', [
            'marcas' => $marcasRepository->findAll(),
            'combustibles' => $combustibleRepository->findAll(),
        ]);
    }
}
