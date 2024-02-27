<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227083152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, commenter_id INT NOT NULL, repondre_id INT NOT NULL, message LONGTEXT NOT NULL, date_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ban TINYINT(1) NOT NULL, INDEX IDX_67F068BCB4D5A9E2 (commenter_id), INDEX IDX_67F068BC5D693660 (repondre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCB4D5A9E2 FOREIGN KEY (commenter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5D693660 FOREIGN KEY (repondre_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD genre_musical_id INT DEFAULT NULL, CHANGE date_creation_compte date_creation_compte DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FFFD05DC FOREIGN KEY (genre_musical_id) REFERENCES genre_musical (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649FFFD05DC ON user (genre_musical_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCB4D5A9E2');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5D693660');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FFFD05DC');
        $this->addSql('DROP INDEX IDX_8D93D649FFFD05DC ON user');
        $this->addSql('ALTER TABLE user DROP genre_musical_id, CHANGE date_creation_compte date_creation_compte DATETIME NOT NULL');
    }
}
