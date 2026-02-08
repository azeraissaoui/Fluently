<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $fullName = $request->request->get('full_name');
            $email = $request->request->get('email');
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $password_confirm = $request->request->get('password_confirm');
            $receiveMail = $request->request->get('receiveMail');
            $termsCondition = $request->request->get('termsCondition');
            $role = $request->request->get('role');
            
            $session = $request->getSession();
            
            // Nettoyer les erreurs précédentes
            $this->clearSessionErrors($session);
            
            $hasError = false;
            
            // VALIDATION 1 : Nom complet (pas de chiffres) - PRIORITÉ 1
            if (empty($fullName)) {
                $session->set('error_full_name', 'Le nom complet est requis.');
                $hasError = true;
            } elseif (preg_match('/\d/', $fullName)) {
                $session->set('error_full_name', 'Le nom ne doit pas contenir de chiffres.');
                $hasError = true;
            } elseif (strlen($fullName) < 2) {
                $session->set('error_full_name', 'Le nom doit contenir au moins 2 caractères.');
                $hasError = true;
            }
            
            // Si erreur sur le nom, on arrête et on affiche seulement cette erreur
            if ($hasError) {
                return $this->render('register/index.html.twig');
            }
            
            // VALIDATION 2 : Email valide - PRIORITÉ 2
            if (empty($email)) {
                $session->set('error_email', 'L\'email est requis.');
                $hasError = true;
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $session->set('error_email', 'Veuillez saisir un email valide.');
                $hasError = true;
            } else {
                $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($existingUser) {
                    $session->set('error_email', 'Cet email est déjà utilisé.');
                    $hasError = true;
                }
            }
            
            if ($hasError) {
                return $this->render('register/index.html.twig');
            }
            
            // VALIDATION 3 : Nom d'utilisateur - PRIORITÉ 3
            if (empty($username)) {
                $session->set('error_username', 'Le nom d\'utilisateur est requis.');
                $hasError = true;
            } elseif (strlen($username) < 3) {
                $session->set('error_username', 'Le nom d\'utilisateur doit contenir au moins 3 caractères.');
                $hasError = true;
            }
            
            if ($hasError) {
                return $this->render('register/index.html.twig');
            }
            
            // VALIDATION 4 : Mot de passe - PRIORITÉ 4
            if (empty($password)) {
                $session->set('error_password', 'Le mot de passe est requis.');
                $hasError = true;
            } elseif (strlen($password) < 6) {
                $session->set('error_password', 'Le mot de passe doit contenir au moins 6 caractères.');
                $hasError = true;
            }
            
            if ($hasError) {
                return $this->render('register/index.html.twig');
            }
            
            // VALIDATION 5 : Confirmation mot de passe - PRIORITÉ 5
            if (empty($password_confirm)) {
                $session->set('error_password_confirm', 'La confirmation du mot de passe est requise.');
                $hasError = true;
            } elseif ($password !== $password_confirm) {
                $session->set('error_password_confirm', 'Les mots de passe ne correspondent pas.');
                $hasError = true;
            }
            
            if ($hasError) {
                return $this->render('register/index.html.twig');
            }
            
            // VALIDATION 6 : Checkboxes - PRIORITÉ 6
            if (!$receiveMail) {
                $session->set('error_receiveMail', 'Vous devez accepter de recevoir les emails.');
                $hasError = true;
            }
            
            if ($hasError) {
                return $this->render('register/index.html.twig');
            }
            
            if (!$termsCondition) {
                $session->set('error_termsCondition', 'Vous devez accepter les termes et conditions.');
                $hasError = true;
            }
            
            if ($hasError) {
                return $this->render('register/index.html.twig');
            }
            
            // Si aucune erreur, créer l'utilisateur
            $user = new User();
            $user->setNom($fullName);
            $user->setPrenom($username);
            $user->setEmail($email);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $user->setRole($role);
            $user->setStatut('online');

            try {
                $em->persist($user);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error saving user: '.$e->getMessage());
                return $this->render('register/index.html.twig');
            }

            $this->addFlash('success', 'Registration successful! You can now log in.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig');
    }
    
    private function clearSessionErrors($session): void
    {
        $errors = [
            'error_full_name', 'error_email', 'error_username', 
            'error_password', 'error_password_confirm', 
            'error_receiveMail', 'error_termsCondition'
        ];
        
        foreach ($errors as $error) {
            $session->remove($error);
        }
    }
}