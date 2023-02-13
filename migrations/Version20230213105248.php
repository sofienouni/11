<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213105248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_images (id INT AUTO_INCREMENT NOT NULL, vente_id INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_B5B9F5D97DC7170A (vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_images ADD CONSTRAINT FK_B5B9F5D97DC7170A FOREIGN KEY (vente_id) REFERENCES ventes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_images DROP FOREIGN KEY FK_B5B9F5D97DC7170A');
        $this->addSql('DROP TABLE client_images');
    }
}
