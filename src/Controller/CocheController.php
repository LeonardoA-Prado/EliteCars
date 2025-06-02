<?php

namespace App\Controller;

use App\Entity\Coche;
use App\Form\CocheType;
use App\Repository\CocheRepository;
use App\Repository\MarcasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CocheController extends AbstractController
{
    #[Route('/', name: 'app_coche_index', methods: ['GET'])]
    public function index(CocheRepository $cocheRepository, MarcasRepository $marcasRepository): Response
    {
        $coches = $cocheRepository->findBy(['vendido' => false]);
        $marcas = $marcasRepository->findAll();
        return $this->render('coche/index.html.twig', [
            'coches' => $coches,
            'marcas' => $marcas,
        ]);
    }

    #[Route('/coche/new', name: 'app_coche_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coche = new Coche();
        $coche->setVendido(false);
        $form = $this->createForm(CocheType::class, $coche, [
            'is_admin' => $this->isGranted('ROLE_ADMIN')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $coche->setVendedor($this->getUser());

            
            $entityManager->persist($coche);
            $entityManager->flush();

            /** @var UploadedFile[] $images */
           $imageForms = $form->get('cochesImages')->all();
            if ($imageForms) {
                $targetDirectory = $this->getParameter('coches_images_directory') . '/' . $coche->getId();
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0755, true);
                }
                $position = 1;
                foreach ($imageForms as $imageForm) {
                    
                    $uploadedFile = $imageForm->get('imageFile')->getData();
                    if ($uploadedFile) {
                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                        try {
                            $uploadedFile->move($targetDirectory, $newFilename);
                        } catch (\Exception $e) {
                            continue;
                        }
                        $cochesImage = $imageForm->getData();
                        
                        $cochesImage->setRutaImagen($newFilename);
                        $cochesImage->setPosicion($position);
                        // Asegúrate de que la relación esté actualizada (si no se hace automáticamente)
                        $cochesImage->setCocheId($coche);
                        $position++;
                    }
                }
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_coche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coche/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/coche/{id}', name: 'app_coche_show',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Coche $coche): Response
    {
        $sortedImages = $coche->getCochesImages()->toArray();
        usort($sortedImages, function($a, $b) {
            return $a->getPosicion() <=> $b->getPosicion();
        });
        return $this->render('coche/show.html.twig', [
            'coche' => $coche,
            'sortedImages' => $sortedImages,
        ]);
    }

    #[Route('/coche/{id}/edit', name: 'app_coche_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coche $coche, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CocheType::class, $coche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Asegurar que el coche se haya actualizado
            $entityManager->flush();

            // Procesa las imágenes nuevas (si las hay)
            $imageForms = $form->get('cochesImages')->all();
            // Directorio de destino
            $targetDirectory = $this->getParameter('coches_images_directory') . '/' . $coche->getId();
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0755, true);
            }
            // Recopilar todas las imágenes (nuevas y existentes)
            $images = [];
            foreach ($imageForms as $imageForm) {
                // Procesa la imagen subida, en caso de haber
                $uploadedFile = $imageForm->get('imageFile')->getData();
                if ($uploadedFile) {
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                    try {
                        $uploadedFile->move($targetDirectory, $newFilename);
                    } catch (\Exception $e) {
                        // Puedes registrar el error o continuar con la siguiente imagen
                        continue;
                    }
                    $cochesImage = $imageForm->getData();
                    $cochesImage->setRutaImagen($newFilename);
                    // Actualiza la relación con el coche
                    $cochesImage->setCocheId($coche);
                    $images[] = $cochesImage;
                    $entityManager->persist($cochesImage);
                } else {
                    // Si no se ha subido una nueva imagen, usamos la imagen existente del formulario
                    $existingImage = $imageForm->getData();
                    if ($existingImage) {
                        $images[] = $existingImage;
                    }
                }
            }
            
            // Ordenar las imágenes por posición actual y reasignar posiciones si es necesario
            usort($images, function ($a, $b) {
                return $a->getPosicion() - $b->getPosicion();
            });
            $position = 1;
            foreach ($images as $image) {
                $image->setPosicion($position);
                $position++;
                $entityManager->persist($image);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_coche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coche/edit.html.twig', [
            'coche' => $coche,
            'form'  => $form,
        ]);
    }

    #[Route('/coche/{id}', name: 'app_coche_delete', methods: ['POST'])]
    public function delete(Request $request, Coche $coche, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coche->getId(), $request->request->get('_token'))) {
            $cocheId = $coche->getId();
            foreach ($coche->getTransaccions() as $transaccion) {
                $entityManager->remove($transaccion);
            }

            $entityManager->remove($coche);
            $entityManager->flush();


            $filesystem = new Filesystem();
            $targetDirectory = $this->getParameter('coches_images_directory') . '/' . $cocheId;
            if (is_dir($targetDirectory)) {
                try {
                    $filesystem->remove($targetDirectory);
                } catch (IOExceptionInterface $exception) {
                   throw new Exception("Error Processing Request", $exception);
                }
            }
        }

        return $this->redirectToRoute('app_miscoches', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mis-coches', name: 'app_miscoches', methods: ['GET'])]
    public function myCoches(CocheRepository $cocheRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        // Si el usuario es admin, mostramos todos los coches, de lo contrario, solo los suyos.
        if ($this->isGranted('ROLE_ADMIN')) {
            $coches = $cocheRepository->findAll();
        } else {
            $coches = $cocheRepository->findBy([
                'vendedor' => $user
            ]);
        }
        
        return $this->render('coche/mis_coches.html.twig', [
            'coches' => $coches,
        ]);
    }

    // Filtros Mediante AJAX
    #[Route('/coche/search', name: 'app_coche_search', methods: ['GET'])]
    public function search(Request $request, CocheRepository $cocheRepository,MarcasRepository $marcasRepository): Response
    {
        $marca = $request->query->get('marca');
        $modelo = $request->query->get('modelo');
        $precioFrom = $request->query->get('precio_from');
        $precioTo = $request->query->get('precio_to');
        $kilometros = $request->query->get('kilometros');
        $carroceria = $request->query->get('carroceria');

        $qb = $cocheRepository->createQueryBuilder('c')->where('c.vendido = false');

        if ($marca) {
            $qb->join('c.marca', 'm')
            ->andWhere('m.nombre = :marca')
            ->setParameter('marca', $marca);
        }
        if ($modelo) {
            $qb->andWhere('c.modelo = :modelo')
            ->setParameter('modelo', $modelo);
        }
        if ($precioFrom) {
        $qb->andWhere('c.precio >= :precioFrom')
           ->setParameter('precioFrom', (float)$precioFrom);
        }
        if ($precioTo) {
            $qb->andWhere('c.precio <= :precioTo')
            ->setParameter('precioTo', (float)$precioTo);
        }
        if ($kilometros) {
            $qb->andWhere('c.kilometros <= :km')
            ->setParameter('km', (int)$kilometros);
        }
        if ($carroceria) {
            $qb->andWhere('c.carroceria = :carroceria')
            ->setParameter('carroceria', $carroceria);
        }

        $coches = $qb->getQuery()->getResult();
        $marcas = $marcasRepository->findAll();
        
        // Si es una petición AJAX, retorna solo el partial
        if ($request->isXmlHttpRequest()) {
            return $this->render('coche/coche_items.html.twig', [
                'coches' => $coches,
                'marcas' => $marcas
            ]);
        }
        
        // En caso contrario, renderiza la página completa
        return $this->render('coche/index.html.twig', [
            'coches' => $coches,
            'marcas' => $marcas
        ]);
    }
}
