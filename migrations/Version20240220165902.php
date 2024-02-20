<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220165902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre_musical_album (genre_musical_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_1A75836BFFFD05DC (genre_musical_id), INDEX IDX_1A75836B1137ABCF (album_id), PRIMARY KEY(genre_musical_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genre_musical_album ADD CONSTRAINT FK_1A75836BFFFD05DC FOREIGN KEY (genre_musical_id) REFERENCES genre_musical (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_musical_album ADD CONSTRAINT FK_1A75836B1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre_musical_album DROP FOREIGN KEY FK_1A75836BFFFD05DC');
        $this->addSql('ALTER TABLE genre_musical_album DROP FOREIGN KEY FK_1A75836B1137ABCF');
        $this->addSql('DROP TABLE genre_musical_album');
    }
}
