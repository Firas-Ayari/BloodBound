<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220003947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation ADD emergency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0D904784C FOREIGN KEY (emergency_id) REFERENCES emergency (id)');
        $this->addSql('CREATE INDEX IDX_31E581A0D904784C ON donation (emergency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0D904784C');
        $this->addSql('DROP INDEX IDX_31E581A0D904784C ON donation');
        $this->addSql('ALTER TABLE donation DROP emergency_id');
    }
}
