<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260910165030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_societe ADD code_pays_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE infos_societe ADD code_ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE infos_societe ADD CONSTRAINT FK_1EDDD61D9E4306D8 FOREIGN KEY (code_pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE infos_societe ADD CONSTRAINT FK_1EDDD61D5EBE781D FOREIGN KEY (code_ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_1EDDD61D9E4306D8 ON infos_societe (code_pays_id)');
        $this->addSql('CREATE INDEX IDX_1EDDD61D5EBE781D ON infos_societe (code_ville_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_societe DROP CONSTRAINT FK_1EDDD61D9E4306D8');
        $this->addSql('ALTER TABLE infos_societe DROP CONSTRAINT FK_1EDDD61D5EBE781D');
        $this->addSql('DROP INDEX IDX_1EDDD61D9E4306D8');
        $this->addSql('DROP INDEX IDX_1EDDD61D5EBE781D');
        $this->addSql('ALTER TABLE infos_societe DROP code_pays_id');
        $this->addSql('ALTER TABLE infos_societe DROP code_ville_id');
    }
}
