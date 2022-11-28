<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221110123023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens ADD ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biens ADD CONSTRAINT FK_1F9004DDA73F0036 FOREIGN KEY (ville_id) REFERENCES villes (id)');
        $this->addSql('CREATE INDEX IDX_1F9004DDA73F0036 ON biens (ville_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens DROP FOREIGN KEY FK_1F9004DDA73F0036');
        $this->addSql('DROP INDEX IDX_1F9004DDA73F0036 ON biens');
        $this->addSql('ALTER TABLE biens DROP ville_id');
    }
}
