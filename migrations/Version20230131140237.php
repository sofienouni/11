<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131140237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_bien (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE biens ADD typebien_id INT NOT NULL, DROP type_bien');
        $this->addSql('ALTER TABLE biens ADD CONSTRAINT FK_1F9004DD677134B4 FOREIGN KEY (typebien_id) REFERENCES type_bien (id)');
        $this->addSql('CREATE INDEX IDX_1F9004DD677134B4 ON biens (typebien_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens DROP FOREIGN KEY FK_1F9004DD677134B4');
        $this->addSql('DROP TABLE type_bien');
        $this->addSql('DROP INDEX IDX_1F9004DD677134B4 ON biens');
        $this->addSql('ALTER TABLE biens ADD type_bien VARCHAR(255) DEFAULT NULL, DROP typebien_id');
    }
}
