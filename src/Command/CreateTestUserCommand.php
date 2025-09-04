<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-test-users',
    description: 'Create test users for the application',
)]
class CreateTestUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Creating Test Users');

        // Create Admin User
        $admin = new Utilisateur();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setPasswordHash('admin123'); // In production, use proper password hashing
        $admin->setRole('ADMIN');
        $admin->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($admin);

        // Create Client User
        $client = new Utilisateur();
        $client->setUsername('client');
        $client->setEmail('client@example.com');
        $client->setPasswordHash('client123'); // In production, use proper password hashing
        $client->setRole('CLIENT');
        $client->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($client);

        $this->entityManager->flush();

        $io->success([
            'Test users created successfully!',
            '',
            'Admin User:',
            '  Username: admin',
            '  Email: admin@example.com',
            '  Password: admin123',
            '  Role: ADMIN',
            '',
            'Client User:',
            '  Username: client',
            '  Email: client@example.com',
            '  Password: client123',
            '  Role: CLIENT',
            '',
            'You can now log in with these credentials to test the system.'
        ]);

        return Command::SUCCESS;
    }
}

