<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $password = $data['password'];

            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $form->get('email')->addError(new \Symfony\Component\Form\FormError('Email not found. Please try again.'));
                return $this->render('login/index.html.twig', ['form' => $form->createView()]);
            }

            if (!password_verify($password, $user->getPassword())) {
                $form->get('password')->addError(new \Symfony\Component\Form\FormError('Incorrect password. Please try again.'));
                return $this->render('login/index.html.twig', ['form' => $form->createView()]);
            }

            // Store user info in session and set status online
            $session->set('user_id', $user->getId());
            $session->set('user_name', $user->getNom());
            $session->set('user_status', 'online');

            $user->setStatut('online');
            $em->flush();

            // Redirect based on role
            if ($user->getRole() === 'admin') {
                return $this->redirectToRoute('dashboard');
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('login/index.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $userId = $session->get('user_id');
        if ($userId) {
            $user = $em->getRepository(User::class)->find($userId);
            if ($user) {
                $user->setStatut('offline'); // Set status offline
                $em->flush();
            }
        }

        $session->clear();

        return $this->redirectToRoute('app_home'); // stay on home page
    }

    #[Route('/logout-ajax', name: 'app_logout_ajax')]
    public function logoutAjax(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if ($userId) {
            $user = $em->getRepository(User::class)->find($userId);
            if ($user) {
                $user->setStatut('offline'); // Set status offline
                $em->flush();
            }
        }

        $session->clear();

        return $this->json(['success' => true]);
    }
}
