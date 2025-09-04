<?php

namespace App\Command;

use App\Repository\LocalRepository;
use App\Repository\ReservationRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-database',
    description: 'Test database connection and queries',
)]
class TestDatabaseCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LocalRepository $localRepository,
        private ReservationRepository $reservationRepository,
        private UtilisateurRepository $utilisateurRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Testing Database Connection and Queries');

        try {
            // Test basic connection
            $io->section('Testing Basic Connection');
            $connection = $this->entityManager->getConnection();
            $connection->executeQuery('SELECT 1');
            $io->success('Database connection successful');

            // Test basic counts
            $io->section('Testing Basic Counts');
            $totalVenues = $this->localRepository->count([]);
            $io->info("Total venues: {$totalVenues}");

            $totalReservations = $this->reservationRepository->count([]);
            $io->info("Total reservations: {$totalReservations}");

            $totalUsers = $this->utilisateurRepository->count([]);
            $io->info("Total users: {$totalUsers}");

            // Test reservation queries
            $io->section('Testing Reservation Queries');
            $pendingReservations = $this->reservationRepository->count(['statut' => 'En attente']);
            $io->info("Pending reservations: {$pendingReservations}");

            $confirmedReservations = $this->reservationRepository->count(['isConfirmee' => true]);
            $io->info("Confirmed reservations: {$confirmedReservations}");

            // Test complex queries
            $io->section('Testing Complex Queries');
            $recentReservations = $this->reservationRepository->createQueryBuilder('r')
                ->leftJoin('r.local', 'l')
                ->leftJoin('r.utilisateur', 'u')
                ->addSelect('l', 'u')
                ->orderBy('r.createdAt', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
            $io->info("Recent reservations query successful: " . count($recentReservations) . " results");

            // Test venue statistics
            $venueStats = $this->reservationRepository->getReservationCountsByVenue();
            $io->info("Venue statistics query successful: " . count($venueStats) . " results");

            // Test monthly data
            $currentYear = date('Y');
            $monthlyData = $this->reservationRepository->getMonthlyReservationCounts($currentYear);
            $io->info("Monthly data query successful: " . count($monthlyData) . " results");
            
            // Test simple monthly data method
            $simpleMonthlyData = $this->reservationRepository->getSimpleMonthlyReservationCounts($currentYear);
            $io->info("Simple monthly data query successful: " . count($simpleMonthlyData) . " results");

            $io->success('All database tests passed!');

        } catch (\Exception $e) {
            $io->error('Database test failed: ' . $e->getMessage());
            $io->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
