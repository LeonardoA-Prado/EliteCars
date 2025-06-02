<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Combustible;
use App\Form\CombustibleType;
use App\Repository\CombustibleRepository;

#[Route('/admin/combustible')]
final class CombustibleController extends AbstractController
{
    #[Route('/new', name: 'admin_combustible_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_coche_index');
        }
        $combustible = new Combustible();
        $form = $this->createForm(CombustibleType::class, $combustible);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($combustible);
            $em->flush();
            return $this->redirectToRoute('admin_panel_index');
        }
        
        return $this->render('admin/combustible/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/edit', name: 'admin_combustible_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Combustible $combustible, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_coche_index');
        }
        $form = $this->createForm(CombustibleType::class, $combustible);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_panel_index');
        }
        
        return $this->render('admin/combustible/edit.html.twig', [
            'form' => $form->createView(),
            'combustible' => $combustible,
        ]);
    }
    
    #[Route('/{id}', name: 'admin_combustible_delete', methods: ['POST'])]
    public function delete(Request $request, Combustible $combustible, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_coche_index');
        }
        if ($this->isCsrfTokenValid('delete' . $combustible->getId(), $request->request->get('_token'))) {
            $em->remove($combustible);
            $em->flush();
        }
        return $this->redirectToRoute('admin_panel_index');
    }
}
