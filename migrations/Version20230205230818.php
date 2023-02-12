<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205230818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes ADD typebien_id INT DEFAULT NULL, DROP type');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489A677134B4 FOREIGN KEY (typebien_id) REFERENCES type_bien (id)');
        $this->addSql('CREATE INDEX IDX_64EC489A677134B4 ON ventes (typebien_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489A677134B4');
        $this->addSql('DROP INDEX IDX_64EC489A677134B4 ON ventes');
        $this->addSql('ALTER TABLE ventes ADD type VARCHAR(255) DEFAULT NULL, DROP typebien_id');
    }
}
