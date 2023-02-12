<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212211721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes ADD ville_id INT DEFAULT NULL, DROP ville');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489AA73F0036 FOREIGN KEY (ville_id) REFERENCES villes (id)');
        $this->addSql('CREATE INDEX IDX_64EC489AA73F0036 ON ventes (ville_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489AA73F0036');
        $this->addSql('DROP INDEX IDX_64EC489AA73F0036 ON ventes');
        $this->addSql('ALTER TABLE ventes ADD ville VARCHAR(100) DEFAULT NULL, DROP ville_id');
    }
}
