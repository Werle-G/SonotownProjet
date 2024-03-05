<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305074309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, reseau_id INT DEFAULT NULL, user_id INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, INDEX IDX_694309E4445D170C (reseau_id), INDEX IDX_694309E4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4445D170C FOREIGN KEY (reseau_id) REFERENCES reseau (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reseau DROP FOREIGN KEY FK_CDE52CB8A76ED395');
        $this->addSql('DROP INDEX UNIQ_CDE52CB8A76ED395 ON reseau');
        $this->addSql('ALTER TABLE reseau ADD nom_site VARCHAR(255) NOT NULL, DROP user_id, DROP twitter, DROP facebook, DROP spotify');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4445D170C');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4A76ED395');
        $this->addSql('DROP TABLE site');
        $this->addSql('ALTER TABLE reseau ADD user_id INT DEFAULT NULL, ADD twitter VARCHAR(255) DEFAULT NULL, ADD facebook VARCHAR(255) DEFAULT NULL, ADD spotify VARCHAR(255) DEFAULT NULL, DROP nom_site');
        $this->addSql('ALTER TABLE reseau ADD CONSTRAINT FK_CDE52CB8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDE52CB8A76ED395 ON reseau (user_id)');
    }
}
