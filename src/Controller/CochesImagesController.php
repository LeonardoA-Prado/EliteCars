<?php

namespace App\Controller;

use App\Entity\CochesImages;
use App\Entity\Coche;
use App\Form\CochesImagesType;
use App\Repository\CochesImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class CochesImagesController extends AbstractController
{

    #[Route('/coche/{id}/images', name: 'coche_manage_images')]
    public function manageImages(Coche $coche, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            // Obtén los parámetros y si son nulos, establece un array vacío manualmente
            $deleteImages = $request->request->get('delete_images') ?? [];
            $positions = $request->request->get('positions') ?? [];

            foreach ($coche->getCochesImages() as $image) {
                $imageId = $image->getId();
                if (in_array($imageId, $deleteImages, true)) {
                    $em->remove($image);
                } elseif (isset($positions[$imageId])) {
                    $newPosition = (int)$positions[$imageId];
                    $image->setPosicion($newPosition);
                }
            }
            $em->flush();
            $this->addFlash('success', 'Imágenes actualizadas correctamente.');
            return $this->redirectToRoute('coche_manage_images', ['id' => $coche->getId()]);
        }

        return $this->render('coche/manage_images.html.twig', [
            'coche' => $coche,
        ]);
    }

    #[Route('/coche/{id}/images/add', name: 'coche_add_images', methods: ['POST'])]
    public function addImages(Coche $coche, Request $request, EntityManagerInterface $em): Response
    {
        $files = $request->files->get('images');
        if ($files) {
            foreach ($files as $file) {
                // Genera un nombre de fichero único para evitar colisiones
                $newFilename = uniqid().'.'.$file->guessExtension();
                $uploadDir = $this->getParameter('images_directory');
                $file->move($uploadDir, $newFilename);

                $image = new CochesImages();
                $image->setRutaImagen('uploads/images/'.$newFilename);
                // Se asigna la posición al final (puedes personalizar la lógica)
                $image->setPosicion(count($coche->getCochesImages()) + 1);
                $image->setCocheId($coche);
                $em->persist($image);
            }
            $em->flush();
            $this->addFlash('success', 'Imágenes añadidas correctamente.');
        }
        return $this->redirectToRoute('coche_manage_images', ['id' => $coche->getId()]);
    }
}
