<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220184121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_genre_musical DROP FOREIGN KEY FK_C221183A1137ABCF');
        $this->addSql('ALTER TABLE album_genre_musical DROP FOREIGN KEY FK_C221183AFFFD05DC');
        $this->addSql('DROP TABLE album_genre_musical');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_genre_musical (album_id INT NOT NULL, genre_musical_id INT NOT NULL, INDEX IDX_C221183A1137ABCF (album_id), INDEX IDX_C221183AFFFD05DC (genre_musical_id), PRIMARY KEY(album_id, genre_musical_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE album_genre_musical ADD CONSTRAINT FK_C221183A1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_genre_musical ADD CONSTRAINT FK_C221183AFFFD05DC FOREIGN KEY (genre_musical_id) REFERENCES genre_musical (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
