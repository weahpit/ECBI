<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260913084608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customize ADD navbar_font_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customize ADD CONSTRAINT FK_5744881CA0BAE8BA FOREIGN KEY (navbar_font_id) REFERENCES color_app (id)');
        $this->addSql('CREATE INDEX IDX_5744881CA0BAE8BA ON customize (navbar_font_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customize DROP CONSTRAINT FK_5744881CA0BAE8BA');
        $this->addSql('DROP INDEX IDX_5744881CA0BAE8BA');
        $this->addSql('ALTER TABLE customize DROP navbar_font_id');
    }
}
