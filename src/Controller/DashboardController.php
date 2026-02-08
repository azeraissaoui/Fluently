<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(EntityManagerInterface $em, SessionInterface $session): Response
    {
        // Récupérer l'utilisateur connecté
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('app_login');
        }

        $currentUser = $em->getRepository(User::class)->find($userId);
        if (!$currentUser) {
            return $this->redirectToRoute('app_login');
        }

        // Stocker les informations de l'utilisateur dans la session pour le dropdown
        $session->set('user_nom', $currentUser->getNom());
        $session->set('user_prenom', $currentUser->getPrenom());
        $session->set('user_email', $currentUser->getEmail());
        $session->set('user_role', $currentUser->getRole());
        $session->set('user_statut', $currentUser->getStatut());

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('dashboard/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/dashboard/delete-user/{id}', name: 'dashboard_delete_user', methods: ['POST'])]
    public function deleteUser(EntityManagerInterface $em, User $user, SessionInterface $session): Response
    {
        // Empêcher l'utilisateur de se supprimer lui-même
        $userId = $session->get('user_id');
        if ($userId == $user->getId()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return $this->redirectToRoute('dashboard');
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');

        return $this->redirectToRoute('dashboard');
    }

    #[Route('/dashboard/edit-user/{id}', name: 'dashboard_edit_user')]
    public function editUser(int $id, Request $request, EntityManagerInterface $em, SessionInterface $session, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Vérifier si l'utilisateur est connecté
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('app_login');
        }

        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $role = $request->request->get('role');
            $statut = $request->request->get('statut');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Validation basique
            if (empty($nom) || empty($prenom) || empty($email) || empty($role)) {
                $this->addFlash('error', 'Tous les champs obligatoires doivent être remplis.');
                return $this->render('dashboard/edit_user.html.twig', [
                    'user' => $user,
                ]);
            }

            // Validation du nom (pas de chiffres)
            if (preg_match('/\d/', $nom)) {
                $this->addFlash('error', 'Le nom ne doit pas contenir de chiffres.');
                return $this->render('dashboard/edit_user.html.twig', [
                    'user' => $user,
                ]);
            }

            // Vérifier si l'email existe déjà pour un autre utilisateur
            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                $this->addFlash('error', 'Cet email est déjà utilisé par un autre utilisateur.');
                return $this->render('dashboard/edit_user.html.twig', [
                    'user' => $user,
                ]);
            }

            // Vérifier les mots de passe s'ils sont remplis
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
                    return $this->render('dashboard/edit_user.html.twig', [
                        'user' => $user,
                    ]);
                }
                
                if ($password !== $confirmPassword) {
                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                    return $this->render('dashboard/edit_user.html.twig', [
                        'user' => $user,
                    ]);
                }
                
                // Hacher le nouveau mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            // Mettre à jour l'utilisateur
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setEmail($email);
            $user->setRole($role);
            if ($statut) {
                $user->setStatut($statut);
            }

            $em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès.');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('dashboard/edit_user.html.twig', [
            'user' => $user,
        ]);
    }
}
