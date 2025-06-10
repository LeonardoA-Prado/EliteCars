<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Coche;
use App\Entity\Transaccion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class TransaccionController extends AbstractController
{
     #[Route('/coche/{id}/comprar', name: 'app_coche_comprar', methods: ['GET'])]
    public function confirmarCompra(Coche $coche): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        // Impedir que el vendedor compre su propio coche.
        if ($coche->getVendedor() && $coche->getVendedor()->getId() == $user->getId()) {
            $this->addFlash('warning', 'No puedes comprar tu propio coche.');
            return $this->redirectToRoute('app_coche_show', ['id' => $coche->getId()]);
        }
        
        return $this->render('transaccion/confirmar_compra.html.twig', [
            'coche' => $coche,
        ]);
    }

    #[Route('/coche/{id}/comprar/confirm', name: 'app_coche_comprar_confirm', methods: ['POST'])]
    public function realizarCompra(Coche $coche, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        // Crear la transacción
        $transaccion = new Transaccion();
        $transaccion->setCoche($coche)
            ->setComprador($user)
            ->setVendedor($coche->getVendedor())
            ->setFechaTransaccion(new \DateTime())
            ->setPrecioTransaccion($coche->getPrecio());

        // Transferir la propiedad: el coche ahora pertenece al comprador.
        $coche->setVendedor($user)
            ->setVendido(true);

        $em->persist($transaccion);
        $em->flush();

        $this->addFlash('success', 'Coche comprado correctamente.');
        return $this->redirectToRoute('app_coche_show', ['id' => $coche->getId()]);
    }

    #[Route('/mis-transacciones', name: 'app_mis_transacciones', methods: ['GET'])]
    public function misTransacciones(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        $repository = $em->getRepository(Transaccion::class);
        // Transacciones donde el usuario es vendedor: coches vendidos.
        $vendidos = $repository->findBy(['vendedor' => $user]);
        // Transacciones donde el usuario es comprador: coches comprados.
        $comprados = $repository->findBy(['comprador' => $user]);

        return $this->render('transaccion/mis_transacciones.html.twig', [
            'vendidos' => $vendidos,
            'comprados' => $comprados,
        ]);
    }
}
