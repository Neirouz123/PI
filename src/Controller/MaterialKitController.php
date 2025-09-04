<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\UtilisateurRepository;
use App\Repository\LocalRepository;
use App\Repository\ReservationRepository;
use App\Service\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MaterialKitController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(
        Request $request,
        SessionInterface $session,
        AuthenticationService $authService,
        LocalRepository $localRepo,
        ReservationRepository $reservationRepo
    ): Response {
        $user = null;
        $error = null;
        $success = null;
        $userReservations = [];

        // ------------------- SIGN IN -------------------
        if ($request->isMethod('POST') && $request->request->has('signin')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Validate input
            if (empty($email) || empty($password)) {
                $error = 'Please enter both email and password';
            } else {
                $user = $authService->authenticate($email, $password);

                if ($user) {
                    // Login user and store session data
                    $authService->login($user, $session);
                    $success = 'Successfully signed in!';
                    
                    // Redirect based on user role with proper error handling
                    try {
                        if ($authService->isAdmin($session)) {
                            // Redirect admin users to dashboard
                            return $this->redirectToRoute('app_admin_dashboard');
                        } else {
                            // Client users stay on the main page
                            $this->addFlash('success', 'Welcome back, ' . $user->getUsername() . '!');
                        }
                    } catch (\Exception $e) {
                        // Log the error and show user-friendly message
                        $error = 'Login successful, but there was an issue with redirection. Please try again.';
                        $authService->logout($session);
                    }
                } else {
                    $error = 'Invalid email or password';
                }
            }
        }

        // ------------------- SIGN OUT -------------------
        if ($request->request->has('signout')) {
            $authService->logout($session);
            $user = null;
            $success = 'Successfully signed out!';
        }

        // ------------------- BOOK VENUE -------------------
        if ($request->isMethod('POST') && $request->request->has('book_venue')) {
            if (!$authService->isAuthenticated($session)) {
                $error = 'Please sign in to book a venue';
            } elseif ($authService->isAdmin($session)) {
                $error = 'Admin users cannot make reservations. Please use a client account.';
            } else {
                $venueId = $request->request->get('venue_id');
                $date = $request->request->get('date');
                $heureDebut = $request->request->get('heure_debut');
                $heureFin = $request->request->get('heure_fin');
                $prix = $request->request->get('prix');

                if (!$venueId || !$date || !$heureDebut || !$heureFin || !$prix) {
                    $error = 'Please fill in all required fields';
                } else {
                    try {
                        // Fetch related entities
                        $local = $localRepo->find($venueId);
                        $userEntity = $authService->getCurrentUser($session);

                        if (!$local || !$userEntity) {
                            $error = 'Invalid venue or user';
                        } else {
                            // Create reservation
                            $reservation = new Reservation();
                            $reservation->setLocal($local);
                            $reservation->setUtilisateur($userEntity);
                            $reservation->setDate(new \DateTime($date));
                            $reservation->setHeureDebut(new \DateTime($heureDebut));
                            $reservation->setHeureFin(new \DateTime($heureFin));
                            $reservation->setPrix((string)$prix);
                            $reservation->setIsConfirmee(false);
                            $reservation->setStatut('En attente');
                            $reservation->setCreatedAt(new \DateTime());

                            $reservationRepo->save($reservation, true);

                            $success = 'Venue booked successfully! We will contact you soon.';
                        }
                    } catch (\Exception $e) {
                        $error = 'Error saving reservation: ' . $e->getMessage();
                    }
                }
            }
        }

        // ------------------- GET CURRENT USER & RESERVATIONS (Only for CLIENT users) -------------------
        if ($authService->validateSession($session)) {
            $user = $authService->getCurrentUser($session);
            if ($user && !$authService->isAdmin($session)) {
                $userReservations = $reservationRepo->findUserReservations($user->getId());
            }
        }

        // ------------------- GET AVAILABLE VENUES (Only for CLIENT users) -------------------
        $venues = [];
        if ($authService->isAuthenticated($session) && !$authService->isAdmin($session)) {
            $venues = $localRepo->findAvailableVenues();
        }

        return $this->render('page/index.html.twig', [
            'user' => $user,
            'error' => $error,
            'success' => $success,
            'venues' => $venues,
            'userReservations' => $userReservations
        ]);
    }

    #[Route('/cart-demo', name: 'app_cart_demo')]
    public function cartDemo(): Response
    {
        return $this->render('page/cart-demo.html.twig');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session, AuthenticationService $authService): Response
    {
        $authService->logout($session);
        $this->addFlash('success', 'Successfully signed out!');
        return $this->redirectToRoute('app_home');
    }
}