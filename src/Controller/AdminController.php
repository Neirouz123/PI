<?php

namespace App\Controller;

use App\Repository\LocalRepository;
use App\Repository\ReservationRepository;
use App\Repository\UtilisateurRepository;
use App\Service\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(
        SessionInterface $session,
        AuthenticationService $authService,
        LocalRepository $localRepository,
        ReservationRepository $reservationRepository,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        // Enhanced authentication check
        if (!$authService->canAccessAdmin($session)) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        try {
            // Get basic statistics first
            $totalVenues = $localRepository->count([]);
            $totalReservations = $reservationRepository->count([]);
            $totalUsers = $utilisateurRepository->count([]);
            $pendingReservations = $reservationRepository->count(['statut' => 'En attente']);
            $confirmedReservations = $reservationRepository->count(['isConfirmee' => true]);

            // Get recent reservations
            $recentReservations = $reservationRepository->createQueryBuilder('r')
                ->leftJoin('r.local', 'l')
                ->leftJoin('r.utilisateur', 'u')
                ->addSelect('l', 'u')
                ->orderBy('r.createdAt', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();

            // Get venue statistics using repository method
            try {
                $venueStats = $reservationRepository->getReservationCountsByVenue();
                // Ensure venueStats is always an array
                $venueStats = is_array($venueStats) ? $venueStats : [];
            } catch (\Exception $e) {
                $venueStats = [];
            }

            // Get monthly reservation data for chart
            $currentYear = date('Y');
            try {
                $monthlyData = $reservationRepository->getMonthlyReservationCounts($currentYear);
                // Validate monthly data
                if (!is_array($monthlyData) || empty($monthlyData)) {
                    $monthlyData = $reservationRepository->getSimpleMonthlyReservationCounts($currentYear);
                }

                // Ensure all values are finite numbers
                $monthlyData = array_map(function ($item) {
                    return [
                        'month' => $item['month'] ?? 0,
                        'count' => is_finite($item['count'] ?? 0) ? (int)$item['count'] : 0
                    ];
                }, $monthlyData);
            } catch (\Exception $e) {
                // Fallback to empty data if all methods fail
                $monthlyData = array_map(function ($month) {
                    return ['month' => $month, 'count' => 0];
                }, range(1, 12));
            }

            return $this->render('admin/dashboard.html.twig', [
                'totalVenues' => $totalVenues,
                'totalReservations' => $totalReservations,
                'totalUsers' => $totalUsers,
                'pendingReservations' => $pendingReservations,
                'confirmedReservations' => $confirmedReservations,
                'recentReservations' => $recentReservations,
                'venueStats' => $venueStats,
                'monthlyData' => $monthlyData,
            ]);
        } catch (\Exception $e) {
            // Log the error and show a simplified dashboard
            $this->addFlash('error', 'Error loading dashboard data: ' . $e->getMessage());

            return $this->render('admin/dashboard.html.twig', [
                'totalVenues' => 0,
                'totalReservations' => 0,
                'totalUsers' => 0,
                'pendingReservations' => 0,
                'confirmedReservations' => 0,
                'recentReservations' => [],
                'venueStats' => [],
                'monthlyData' => [],
            ]);
        }
    }

    #[Route('/venues', name: 'app_admin_venues')]
    public function venues(LocalRepository $localRepository, SessionInterface $session, AuthenticationService $authService): Response
    {
        // Enhanced authentication check
        if (!$authService->canAccessAdmin($session)) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $venues = $localRepository->findAll();

        return $this->render('admin/venues.html.twig', [
            'venues' => $venues,
        ]);
    }

    #[Route('/reservations', name: 'app_admin_reservations')]
    public function reservations(ReservationRepository $reservationRepository, SessionInterface $session, AuthenticationService $authService): Response
    {
        // Enhanced authentication check
        if (!$authService->canAccessAdmin($session)) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $reservations = $reservationRepository->createQueryBuilder('r')
            ->leftJoin('r.local', 'l')
            ->leftJoin('r.utilisateur', 'u')
            ->addSelect('l', 'u')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('admin/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(UtilisateurRepository $utilisateurRepository, SessionInterface $session, AuthenticationService $authService): Response
    {
        // Enhanced authentication check
        if (!$authService->canAccessAdmin($session)) {
            $this->addFlash('error', 'Access denied. Admin privileges required.');
            return $this->redirectToRoute('app_home');
        }

        $users = $utilisateurRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/test', name: 'app_admin_test')]
    public function test(SessionInterface $session, AuthenticationService $authService): Response
    {
        // Simple test route to check if admin access works
        if (!$authService->canAccessAdmin($session)) {
            return new Response('Access denied. Admin privileges required.');
        }

        return new Response('Admin test route working! User: ' . $session->get('user_username') . ', Role: ' . $session->get('user_role'));
    }
}
