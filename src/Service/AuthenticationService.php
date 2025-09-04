<?php

namespace App\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationService
{
    public function __construct(
        private UtilisateurRepository $userRepository
    ) {}

    /**
     * Authenticate user with email and password
     */
    public function authenticate(string $email, string $password): ?Utilisateur
    {
        if (empty($email) || empty($password)) {
            return null;
        }

        return $this->userRepository->authenticate($email, $password);
    }

    /**
     * Login user and store session data
     */
    public function login(Utilisateur $user, SessionInterface $session): void
    {
        $session->set('user_id', $user->getId());
        $session->set('user_email', $user->getEmail());
        $session->set('user_username', $user->getUsername());
        $session->set('user_role', $user->getRole());
        $session->set('user_authenticated', true);
    }

    /**
     * Logout user and clear session
     */
    public function logout(SessionInterface $session): void
    {
        $session->clear();
    }

    /**
     * Check if user is authenticated
     */
    public function isAuthenticated(SessionInterface $session): bool
    {
        return $session->has('user_id') && 
               $session->has('user_authenticated') && 
               $session->get('user_authenticated') === true;
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin(SessionInterface $session): bool
    {
        if (!$this->isAuthenticated($session)) {
            return false;
        }

        $userRole = $session->get('user_role');
        return $userRole && strtoupper(trim($userRole)) === 'ADMIN';
    }

    /**
     * Get current authenticated user
     */
    public function getCurrentUser(SessionInterface $session): ?Utilisateur
    {
        if (!$this->isAuthenticated($session)) {
            return null;
        }

        $userId = $session->get('user_id');
        return $this->userRepository->find($userId);
    }

    /**
     * Validate user session and refresh if needed
     */
    public function validateSession(SessionInterface $session): bool
    {
        if (!$this->isAuthenticated($session)) {
            return false;
        }

        $user = $this->getCurrentUser($session);
        if (!$user) {
            // User not found in database, clear session
            $this->logout($session);
            return false;
        }

        return true;
    }

    /**
     * Get user role from session
     */
    public function getUserRole(SessionInterface $session): ?string
    {
        if (!$this->isAuthenticated($session)) {
            return null;
        }

        return $session->get('user_role');
    }

    /**
     * Check if user can access admin area
     */
    public function canAccessAdmin(SessionInterface $session): bool
    {
        return $this->isAuthenticated($session) && $this->isAdmin($session);
    }
}
