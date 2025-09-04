<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902204745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE local_image DROP FOREIGN KEY local_image_ibfk_1');
        $this->addSql('DROP TABLE local_image');
        $this->addSql('DROP INDEX idx_local_disponible ON local');
        $this->addSql('DROP INDEX idx_local_nom ON local');
        $this->addSql('ALTER TABLE local CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE prix prix DOUBLE PRECISION NOT NULL, CHANGE disponible disponible TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_2');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('ALTER TABLE reservation CHANGE isConfirmee isConfirmee TINYINT(1) NOT NULL, CHANGE statut statut VARCHAR(50) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX local_id ON reservation');
        $this->addSql('CREATE INDEX IDX_42C849555D5A2101 ON reservation (local_id)');
        $this->addSql('DROP INDEX utilisateur_id ON reservation');
        $this->addSql('CREATE INDEX IDX_42C84955FB88E14F ON reservation (utilisateur_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_2 FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (local_id) REFERENCES local (id)');
        $this->addSql('DROP INDEX username ON utilisateur');
        $this->addSql('DROP INDEX email ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur CHANGE username username VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password_hash password_hash VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(50) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE local_image (id INT AUTO_INCREMENT NOT NULL, local_id INT NOT NULL, image_data LONGBLOB NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX local_id (local_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE local_image ADD CONSTRAINT local_image_ibfk_1 FOREIGN KEY (local_id) REFERENCES local (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE local CHANGE adresse adresse VARCHAR(500) NOT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE prix prix NUMERIC(10, 2) NOT NULL, CHANGE disponible disponible TINYINT(1) DEFAULT 1, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('CREATE INDEX idx_local_disponible ON local (disponible)');
        $this->addSql('CREATE INDEX idx_local_nom ON local (nom)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555D5A2101');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FB88E14F');
        $this->addSql('ALTER TABLE reservation CHANGE isConfirmee isConfirmee TINYINT(1) DEFAULT 0, CHANGE statut statut VARCHAR(50) DEFAULT \'pending\', CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX idx_42c849555d5a2101 ON reservation');
        $this->addSql('CREATE INDEX local_id ON reservation (local_id)');
        $this->addSql('DROP INDEX idx_42c84955fb88e14f ON reservation');
        $this->addSql('CREATE INDEX utilisateur_id ON reservation (utilisateur_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849555D5A2101 FOREIGN KEY (local_id) REFERENCES local (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE username username VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE password_hash password_hash VARCHAR(255) DEFAULT NULL, CHANGE role role VARCHAR(255) DEFAULT \'CLIENT\' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX username ON utilisateur (username)');
        $this->addSql('CREATE UNIQUE INDEX email ON utilisateur (email)');
    }
}
