<?php


namespace App\Controller;

use App\Entity\CochesImages;
use App\Entity\Coche;
use App\Form\CochesImagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CochesImagesController extends AbstractController
{
    #[Route('/coche/{id}/images', name: 'coche_manage_images')]
public function manageImages(Coche $coche, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createFormBuilder($coche)
        ->add('cochesImages', CollectionType::class, [
            'entry_type' => CochesImagesType::class,
            'allow_add' => false,
            'allow_delete' => true,
            'by_reference' => false,
        ])
        ->getForm();
        
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        
        foreach ($coche->getCochesImages() as $image) {
            $imageForm = $form->get('cochesImages')->get($image->getId());
            $delete = $imageForm->get('delete')->getData();
            if ($delete) {
                $em->remove($image);
                continue;
            }
            $newFile = $imageForm->get('imageFile')->getData();
            if ($newFile) {
                $targetDirectory = $this->getParameter('coches_images_directory') . '/' . $coche->getId();
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0755, true);
                }
                $originalFilename = pathinfo($newFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $newFile->guessExtension();
                try {
                    $newFile->move($targetDirectory, $newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Error al reemplazar la imagen.');
                    continue;
                }
                $image->setRutaImagen($newFilename);
            }
        }
        $em->flush();
        $this->addFlash('success', 'Imágenes actualizadas correctamente.');
        return $this->redirectToRoute('coche_manage_images', ['id' => $coche->getId()]);
    }
    
    return $this->render('coche/manage_images.html.twig', [
        'coche' => $coche,
        'form'  => $form->createView(),
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
                    } catch (\Exception $e) {
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