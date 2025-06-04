<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Marcas;
use App\Form\MarcasType;
use App\Repository\MarcasRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/marcas')]
final class MarcasController extends AbstractController
{
    #[Route('/new', name: 'admin_marcas_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_coche_index');
        }
        $marca = new Marcas();
        $form = $this->createForm(MarcasType::class, $marca);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($marca);
            $em->flush();
            return $this->redirectToRoute('admin_panel_index');
        }
        
        return $this->render('admin/marcas/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/edit', name: 'admin_marcas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Marcas $marca, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_coche_index');
        }
        $form = $this->createForm(MarcasType::class, $marca);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_panel_index');
        }
        
        return $this->render('admin/marcas/edit.html.twig', [
            'form' => $form->createView(),
            'marca' => $marca,
        ]);
    }
    
    #[Route('/{id}', name: 'admin_marcas_delete', methods: ['POST'])]
    public function delete(Request $request, Marcas $marca, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_coche_index');
        }
        if ($this->isCsrfTokenValid('delete' . $marca->getId(), $request->request->get('_token'))) {
            $em->remove($marca);
            $em->flush();
        }
        return $this->redirectToRoute('admin_panel_index');
    }
}
