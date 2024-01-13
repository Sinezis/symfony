<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240113194734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE program_user DROP FOREIGN KEY FK_8075834E3EB8070A');
        $this->addSql('ALTER TABLE program_user DROP FOREIGN KEY FK_8075834EA76ED395');
        $this->addSql('DROP TABLE program_user');
        $this->addSql('ALTER TABLE program ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED77847E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_92ED77847E3C61F9 ON program (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE program_user (program_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8075834E3EB8070A (program_id), INDEX IDX_8075834EA76ED395 (user_id), PRIMARY KEY(program_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE program_user ADD CONSTRAINT FK_8075834E3EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE program_user ADD CONSTRAINT FK_8075834EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED77847E3C61F9');
        $this->addSql('DROP INDEX IDX_92ED77847E3C61F9 ON program');
        $this->addSql('ALTER TABLE program DROP owner_id');
    }
}
