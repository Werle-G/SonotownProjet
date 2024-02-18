<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218214057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E434251576F');
        $this->addSql('DROP INDEX IDX_39986E434251576F ON album');
        $this->addSql('ALTER TABLE album CHANGE produirs_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_39986E43A76ED395 ON album (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43A76ED395');
        $this->addSql('DROP INDEX IDX_39986E43A76ED395 ON album');
        $this->addSql('ALTER TABLE album CHANGE user_id produirs_id INT NOT NULL');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E434251576F FOREIGN KEY (produirs_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_39986E434251576F ON album (produirs_id)');
    }
}
