<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Reservation[]    findOneBy(array $criteria, array $orderBy = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Get all reservations for a specific user
     */
    public function findUserReservations(int $userId): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.local', 'l')
            ->leftJoin('r.utilisateur', 'u')
            ->addSelect('l', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('r.date', 'DESC')
            ->addOrderBy('r.heureDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a reservation by its ID
     */
    public function findReservationById(int $id): ?Reservation
    {
        return $this->find($id);
    }

    /**
     * Get monthly reservation counts for a specific year
     */
    public function getMonthlyReservationCounts(int $year): array
    {
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            try {
                // Create start and end dates for the month
                $startDate = new \DateTime("{$year}-{$month}-01 00:00:00");
                $endDate = clone $startDate;
                $endDate->modify('last day of this month')->setTime(23, 59, 59);

                $count = $this->createQueryBuilder('r')
                    ->select('COUNT(r.id)')
                    ->where('r.date >= :startDate')
                    ->andWhere('r.date <= :endDate')
                    ->setParameter('startDate', $startDate->format('Y-m-d'))
                    ->setParameter('endDate', $endDate->format('Y-m-d'))
                    ->getQuery()
                    ->getSingleScalarResult();

                // Ensure count is a valid number and not infinite
                $count = is_numeric($count) ? (int)$count : 0;
                $count = is_finite($count) ? $count : 0;
            } catch (\Exception $e) {
                $count = 0;
            }

            $monthlyData[] = [
                'month' => $month,
                'count' => $count
            ];
        }

        return $monthlyData;
    }

    /**
     * Get reservation counts by venue
     */
    public function getReservationCountsByVenue(): array
    {
        try {
            $result = $this->createQueryBuilder('r')
                ->select('l.nom as venueName, COUNT(r.id) as reservationCount')
                ->leftJoin('r.local', 'l')
                ->groupBy('l.id', 'l.nom')
                ->orderBy('reservationCount', 'DESC')
                ->setMaxResults(10) // Limit to top 10 venues
                ->getQuery()
                ->getResult();

            // Ensure we have proper data structure
            return array_map(function ($item) {
                return [
                    'venueName' => $item['venueName'] ?? 'Unknown Venue',
                    'reservationCount' => (int)($item['reservationCount'] ?? 0)
                ];
            }, $result);
        } catch (\Exception $e) {
            // Return empty array if there's an error
            return [];
        }
    }

    /**
     * Get simple monthly reservation counts (fallback method)
     */
    public function getSimpleMonthlyReservationCounts(int $year): array
    {
        $monthlyData = [];

        // Initialize all months with 0
        for ($month = 1; $month <= 12; $month++) {
            $monthlyData[] = [
                'month' => $month,
                'count' => 0
            ];
        }

        try {
            // Get all reservations for the year and group by month
            $reservations = $this->createQueryBuilder('r')
                ->select('r.date')
                ->where('YEAR(r.date) = :year')
                ->setParameter('year', $year)
                ->getQuery()
                ->getResult();

            // Count reservations by month
            foreach ($reservations as $reservation) {
                $date = $reservation['date'];
                if ($date instanceof \DateTimeInterface) {
                    $month = (int)$date->format('n'); // n = month without leading zeros
                    if ($month >= 1 && $month <= 12) {
                        $monthlyData[$month - 1]['count']++;
                    }
                }
            }
        } catch (\Exception $e) {
            // If there's an error, return empty data
            // The monthlyData array is already initialized with zeros
        }

        return $monthlyData;
    }

    /**
     * Persist a reservation entity
     */
    public function save(Reservation $reservation, bool $flush = false): void
    {
        $em = $this->getEntityManager();
        $em->persist($reservation);

        if ($flush) {
            $em->flush();
        }
    }
}
