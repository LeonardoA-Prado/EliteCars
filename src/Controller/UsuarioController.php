<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use App\Repository\CocheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/usuario')]
final class UsuarioController extends AbstractController
{
    #[Route(name: 'app_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_usuario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);

        if (!$this->isGranted('ROLE_ADMIN')) {
            $form->remove('roles');
            $usuario->setRoles(['ROLE_USER']);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener la contraseña en texto plano del formulario
            $plainPassword = $form->get('contrasena')->getData();
            if (!$plainPassword) {
                $this->addFlash('error', 'La contraseña es obligatoria.');
                return $this->render('usuario/new.html.twig', [
                    'usuario' => $usuario,
                    'form' => $form->createView(),
                ]);
            }
            $hashedPassword = $passwordHasher->hashPassword($usuario, $plainPassword);
            $usuario->setContrasena($hashedPassword);

            $entityManager->persist($usuario);
            $entityManager->flush();

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_usuario_show', methods: ['GET'])]
    public function show(Usuario $usuario): Response
    {
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_usuario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Usuario $usuario, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UsuarioType::class, $usuario, [
            'is_admin' => $this->isGranted('ROLE_ADMIN'),
            'edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('contrasena')->getData(); 
            if (!empty($plainPassword)) {
                $hashedPassword = $passwordHasher->hashPassword($usuario, $plainPassword);
                $usuario->setContrasena($hashedPassword);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_usuario_show', ['id' => $usuario->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_usuario_delete', methods: ['POST'])]
    public function delete(Request $request, Usuario $usuario, EntityManagerInterface $entityManager, CocheRepository $cocheRepository): Response {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->get('_token'))) {
            // Buscar coches vinculados al usuario
            $coches = $cocheRepository->findBy(['vendedor' => $usuario]);
            
            // Si hay coches y no se ha confirmado la eliminación conjunta...
            if (!$request->request->get('confirm_deletion')) {
                if (count($coches) > 0) {
                    $message = sprintf(
                        'Su cuenta tiene %d coche(s) vinculado(s). Por favor, marque la casilla para confirmar la eliminación de su cuenta y todos los coches vinculados.',
                        count($coches)
                    );
                } else {
                    $message = 'Debes confirmar que desea borrar su cuenta.';
                }
                $this->addFlash('warning', $message);
                return $this->redirectToRoute('app_usuario_show', ['id' => $usuario->getId()]);
            }
            
            // Almacenar los IDs de los coches para borrar sus carpetas luego
            $carIds = [];
            foreach ($coches as $coche) {
                $carIds[] = $coche->getId();
                $entityManager->remove($coche);
            }
            
            // Luego eliminar el usuario
            $entityManager->remove($usuario);
            $entityManager->flush();
            
            // Eliminar las carpetas de imágenes de cada coche eliminado
            $filesystem = new \Symfony\Component\Filesystem\Filesystem();
            foreach ($carIds as $carId) {
                $targetDirectory = $this->getParameter('coches_images_directory') . '/' . $carId;
                if (is_dir($targetDirectory)) {
                    try {
                        $filesystem->remove($targetDirectory);
                    } catch (\Symfony\Component\Filesystem\Exception\IOExceptionInterface $exception) {
                        throw new \Exception("Error processing request", $exception);
                    }
                }
            }
            
            // Si el usuario eliminado es el actualmente autenticado, cerramos la sesión
            if ($this->getUser() && $this->getUser()->getId() === $usuario->getId()) {
                $this->container->get('security.token_storage')->setToken(null);
                $request->getSession()->invalidate();
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }
        }
        
        return $this->redirectToRoute('app_coche_index', [], Response::HTTP_SEE_OTHER);
    }
}
