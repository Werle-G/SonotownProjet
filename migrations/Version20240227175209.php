<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227175209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playlist (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom_playlist VARCHAR(255) NOT NULL, INDEX IDX_D782112DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist_piste (playlist_id INT NOT NULL, piste_id INT NOT NULL, INDEX IDX_FAFE49346BBD148 (playlist_id), INDEX IDX_FAFE4934C34065BC (piste_id), PRIMARY KEY(playlist_id, piste_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playlist_piste ADD CONSTRAINT FK_FAFE49346BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_piste ADD CONSTRAINT FK_FAFE4934C34065BC FOREIGN KEY (piste_id) REFERENCES piste (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112DA76ED395');
        $this->addSql('ALTER TABLE playlist_piste DROP FOREIGN KEY FK_FAFE49346BBD148');
        $this->addSql('ALTER TABLE playlist_piste DROP FOREIGN KEY FK_FAFE4934C34065BC');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_piste');
    }
}
