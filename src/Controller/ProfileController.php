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

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $em, SessionInterface $session, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Récupérer l'utilisateur connecté
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('app_login');
        }

        $user = $em->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException("Utilisateur non trouvé.");
        }

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $role = $request->request->get('role');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');
            
            // Nettoyer les erreurs précédentes
            $this->clearSessionErrors($session);
            
            $hasError = false;
            
            // VALIDATION 1 : Nom (pas de chiffres) - PRIORITÉ 1
            if (empty($nom)) {
                $session->set('error_nom', 'Le nom est requis.');
                $hasError = true;
            } elseif (preg_match('/\d/', $nom)) {
                $session->set('error_nom', 'Le nom ne doit pas contenir de chiffres.');
                $hasError = true;
            } elseif (strlen($nom) < 2) {
                $session->set('error_nom', 'Le nom doit contenir au moins 2 caractères.');
                $hasError = true;
            }
            
            if ($hasError) {
                return $this->render('profile/index.html.twig', [
                    'user' => $user,
                ]);
            }
            
            // VALIDATION 2 : Prénom (username) - PRIORITÉ 2
            if (empty($prenom)) {
                $session->set('error_prenom', 'Le prénom est requis.');
                $hasError = true;
            } elseif (strlen($prenom) < 2) {
                $session->set('error_prenom', 'Le prénom doit contenir au moins 2 caractères.');
                $hasError = true;
            }
            
            if ($hasError) {
                return $this->render('profile/index.html.twig', [
                    'user' => $user,
                ]);
            }
            
            // VALIDATION 3 : Email - PRIORITÉ 3
            if (empty($email)) {
                $session->set('error_email', 'L\'email est requis.');
                $hasError = true;
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $session->set('error_email', 'Veuillez saisir un email valide.');
                $hasError = true;
            } else {
                // Vérifier si l'email existe déjà (pour un autre utilisateur)
                $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($existingUser && $existingUser->getId() !== $user->getId()) {
                    $session->set('error_email', 'Cet email est déjà utilisé par un autre compte.');
                    $hasError = true;
                }
            }
            
            if ($hasError) {
                return $this->render('profile/index.html.twig', [
                    'user' => $user,
                ]);
            }
            
            // VALIDATION 4 : Mot de passe (si rempli) - PRIORITÉ 4
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $session->set('error_password', 'Le mot de passe doit contenir au moins 6 caractères.');
                    $hasError = true;
                } elseif ($password !== $confirmPassword) {
                    $session->set('error_confirm_password', 'Les mots de passe ne correspondent pas.');
                    $hasError = true;
                }
                
                if ($hasError) {
                    return $this->render('profile/index.html.twig', [
                        'user' => $user,
                    ]);
                }
            }
            
            // VALIDATION 5 : Rôle - PRIORITÉ 5
            if (empty($role)) {
                $session->set('error_role', 'Le rôle est requis.');
                $hasError = true;
            }
            
            if ($hasError) {
                return $this->render('profile/index.html.twig', [
                    'user' => $user,
                ]);
            }
            
            // Si aucune erreur, mettre à jour l'utilisateur
            $user->setNom($nom);
            $user->setPrenom($prenom);  // prénom = username
            $user->setEmail($email);
            $user->setRole($role);

            // Si le mot de passe est rempli, le hacher
            if (!empty($password)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            $em->persist($user);
            $em->flush();

            $session->set('user_name', $user->getNom());
            
            // Nettoyer les erreurs après succès
            $this->clearSessionErrors($session);

            $this->addFlash('success', 'Profil mis à jour avec succès !');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
    
    private function clearSessionErrors($session): void
    {
        $errors = [
            'error_nom', 'error_prenom', 'error_email',
            'error_password', 'error_confirm_password', 'error_role'
        ];
        
        foreach ($errors as $error) {
            $session->remove($error);
        }
    }
}