<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304215519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reseau DROP FOREIGN KEY FK_CDE52CB8A76ED395');
        $this->addSql('DROP INDEX UNIQ_CDE52CB8A76ED395 ON reseau');
        $this->addSql('ALTER TABLE reseau ADD nom_site VARCHAR(255) NOT NULL, DROP user_id, DROP twitter, DROP facebook, DROP spotify');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reseau ADD user_id INT DEFAULT NULL, ADD twitter VARCHAR(255) DEFAULT NULL, ADD facebook VARCHAR(255) DEFAULT NULL, ADD spotify VARCHAR(255) DEFAULT NULL, DROP nom_site');
        $this->addSql('ALTER TABLE reseau ADD CONSTRAINT FK_CDE52CB8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDE52CB8A76ED395 ON reseau (user_id)');
    }
}
