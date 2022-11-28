<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106143957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE biens (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT DEFAULT NULL, pieces VARCHAR(100) DEFAULT NULL, surface VARCHAR(100) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, etage VARCHAR(100) DEFAULT NULL, chauffage VARCHAR(100) DEFAULT NULL, climatisation VARCHAR(100) DEFAULT NULL, ascenceur VARCHAR(100) DEFAULT NULL, concierge TINYINT(1) DEFAULT NULL, gardien TINYINT(1) DEFAULT NULL, cideosurveillance TINYINT(1) DEFAULT NULL, maisongardien TINYINT(1) DEFAULT NULL, eclairageexterieur TINYINT(1) DEFAULT NULL, prix VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE biens');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
