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
            // Actualiza posiciones y elimina imágenes seleccionadas
            $deleteImages = $request->request->get('delete_images') ?? [];
            $positions = $request->request->get('positions') ?? [];

            foreach ($coche->getCochesImages() as $image) {
                $imageId = $image->getId();
                if (in_array($imageId, $deleteImages, true)) {
                    $em->remove($image);
                } elseif (isset($positions[$imageId])) {
                    $image->setPosicion((int) $positions[$imageId]);
                }
            }

            // Manejo de nuevas imágenes (si se han subido)
            $images = $request->files->get('images');
            if ($images) {
                $targetDirectory = $this->getParameter('coches_images_directory') . '/' . $coche->getId();
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0755, true);
                }
                foreach ($images as $imageFile) {
                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                        try {
                            $imageFile->move($targetDirectory, $newFilename);
                        } catch (FileException $e) {
                            $this->addFlash('danger', 'Error al subir la imagen.');
                            continue;
                        }
                        $newImage = new CochesImages();
                        // Se guarda solo el nombre del archivo; puedes ajustar la ruta según tus necesidades
                        $newImage->setRutaImagen($newFilename);
                        // Se asigna la posición al final (puedes personalizar la lógica)
                        $newImage->setPosicion(count($coche->getCochesImages()) + 1);
                        $newImage->setCocheId($coche);
                        $em->persist($newImage);
                    }
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
        $images = $request->files->get('images');
        if ($images) {
            $targetDirectory = $this->getParameter('coches_images_directory') . '/' . $coche->getId();
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0755, true);
            }
            foreach ($images as $imageFile) {
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                    try {
                        $imageFile->move($targetDirectory, $newFilename);
                    } catch (FileException $e) {
                        $this->addFlash('danger', 'Error al subir la imagen.');
                        continue;
                    }
                    $newImage = new CochesImages();
                    $newImage->setRutaImagen($newFilename);
                    $newImage->setPosicion(count($coche->getCochesImages()) + 1);
                    $newImage->setCocheId($coche);
                    $em->persist($newImage);
                }
            }
            $em->flush();
            $this->addFlash('success', 'Imágenes añadidas correctamente.');
        }
        return $this->redirectToRoute('coche_manage_images', ['id' => $coche->getId()]);
    }
}
