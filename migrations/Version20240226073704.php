<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226073704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_genre_musical (album_id INT NOT NULL, genre_musical_id INT NOT NULL, INDEX IDX_C221183A1137ABCF (album_id), INDEX IDX_C221183AFFFD05DC (genre_musical_id), PRIMARY KEY(album_id, genre_musical_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_concert (user_id INT NOT NULL, concert_id INT NOT NULL, INDEX IDX_8D711CD8A76ED395 (user_id), INDEX IDX_8D711CD883C97B2E (concert_id), PRIMARY KEY(user_id, concert_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album_genre_musical ADD CONSTRAINT FK_C221183A1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_genre_musical ADD CONSTRAINT FK_C221183AFFFD05DC FOREIGN KEY (genre_musical_id) REFERENCES genre_musical (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_concert ADD CONSTRAINT FK_8D711CD8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_concert ADD CONSTRAINT FK_8D711CD883C97B2E FOREIGN KEY (concert_id) REFERENCES concert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_musical_album DROP FOREIGN KEY FK_1A75836B1137ABCF');
        $this->addSql('ALTER TABLE genre_musical_album DROP FOREIGN KEY FK_1A75836BFFFD05DC');
        $this->addSql('DROP TABLE genre_musical_album');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre_musical_album (genre_musical_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_1A75836B1137ABCF (album_id), INDEX IDX_1A75836BFFFD05DC (genre_musical_id), PRIMARY KEY(genre_musical_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE genre_musical_album ADD CONSTRAINT FK_1A75836B1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_musical_album ADD CONSTRAINT FK_1A75836BFFFD05DC FOREIGN KEY (genre_musical_id) REFERENCES genre_musical (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album_genre_musical DROP FOREIGN KEY FK_C221183A1137ABCF');
        $this->addSql('ALTER TABLE album_genre_musical DROP FOREIGN KEY FK_C221183AFFFD05DC');
        $this->addSql('ALTER TABLE user_concert DROP FOREIGN KEY FK_8D711CD8A76ED395');
        $this->addSql('ALTER TABLE user_concert DROP FOREIGN KEY FK_8D711CD883C97B2E');
        $this->addSql('DROP TABLE album_genre_musical');
        $this->addSql('DROP TABLE user_concert');
    }
}
