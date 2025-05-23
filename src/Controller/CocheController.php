<?php

namespace App\Controller;

use App\Entity\Coche;
use App\Form\CocheType;
use App\Repository\CocheRepository;
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
    public function index(CocheRepository $cocheRepository): Response
    {
        return $this->render('coche/index.html.twig', [
            'coches' => $cocheRepository->findAll(),
        ]);
    }

    #[Route('/coche/new', name: 'app_coche_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coche = new Coche();
        $form = $this->createForm(CocheType::class, $coche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Asocia el coche al usuario logueado
            $coche->setVendedor($this->getUser());

            // Persistimos el coche para tener su ID
            $entityManager->persist($coche);
            $entityManager->flush();

            /** @var UploadedFile[] $images */
            $images = $form->get('images')->getData();
            if ($images) {
                $targetDirectory = $this->getParameter('coches_images_directory') . '/' . $coche->getId();
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0755, true);
                }
                foreach ($images as $imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                    try {
                        $imageFile->move($targetDirectory, $newFilename);
                    } catch (FileException $e) {
                        // Manejar error
                    }
                    $cochesImage = new \App\Entity\CochesImages();
                    $cochesImage->setRutaImagen($newFilename);
                    $cochesImage->setCocheId($coche);
                    $entityManager->persist($cochesImage);
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
        return $this->render('coche/show.html.twig', [
            'coche' => $coche,
        ]);
    }

    #[Route('/coche/{id}/edit', name: 'app_coche_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coche $coche, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CocheType::class, $coche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

        return $this->redirectToRoute('app_coche_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mis-coches', name: 'app_miscoches', methods: ['GET'])]
    public function myCoches(CocheRepository $cocheRepository): Response
    {
        // Obtenemos el usuario logueado
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        // Se asume que en la entidad Coche, el campo 'vendedor' almacena el usuario relacionado.
        $coches = $cocheRepository->findBy([
            'vendedor' => $user
        ]);
        
        return $this->render('coche/mis_coches.html.twig', [
            'coches' => $coches,
        ]);
    }

    // Filtros Mediante AJAX
    #[Route('/coche/search', name: 'app_coche_search', methods: ['GET'])]
    public function search(Request $request, CocheRepository $cocheRepository): Response
    {
        $marca = $request->query->get('marca');
        $modelo = $request->query->get('modelo');
        $precioFrom = $request->query->get('precio_from');
        $precioTo = $request->query->get('precio_to');
        $kilometros = $request->query->get('kilometros');
        $carroceria = $request->query->get('carroceria');

        $qb = $cocheRepository->createQueryBuilder('c');

        if ($marca) {
            $qb->andWhere('c.marca = :marca')
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
        
        // Si es una petición AJAX, retorna solo el partial
        if ($request->isXmlHttpRequest()) {
            return $this->render('coche/coche_items.html.twig', [
                'coches' => $coches
            ]);
        }
        
        // En caso contrario, renderiza la página completa
        return $this->render('coche/index.html.twig', [
            'coches' => $coches
        ]);
    }
}
