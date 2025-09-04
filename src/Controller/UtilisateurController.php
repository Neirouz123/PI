<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password before saving
            $utilisateur->setPasswordHash(password_hash($utilisateur->getPasswordHash(), PASSWORD_DEFAULT));

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'User "' . $utilisateur->getUsername() . '" has been created successfully!');
            return $this->redirectToRoute('app_admin_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Only hash password if it was changed
            $plainPassword = $form->get('password_hash')->getData();
            if (!empty($plainPassword)) {
                $utilisateur->setPasswordHash(password_hash($plainPassword, PASSWORD_DEFAULT));
            }

            $entityManager->flush();

            $this->addFlash('success', 'User "' . $utilisateur->getUsername() . '" has been updated successfully!');
            return $this->redirectToRoute('app_admin_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $username = $utilisateur->getUsername();
            $entityManager->remove($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'User "' . $username . '" has been deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid security token. Please try again.');
        }

        return $this->redirectToRoute('app_admin_users', [], Response::HTTP_SEE_OTHER);
    }
}
