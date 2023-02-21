<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221142449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE donation (id INT AUTO_INCREMENT NOT NULL, emergency_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, don_location VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number BIGINT NOT NULL, donation_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_31E581A0D904784C (emergency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, facility_id INT DEFAULT NULL, weekday DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', hourtime TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', INDEX IDX_D499BFF6A7014910 (facility_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0D904784C FOREIGN KEY (emergency_id) REFERENCES emergency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A7014910 FOREIGN KEY (facility_id) REFERENCES facility (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment ADD facility_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD description VARCHAR(255) NOT NULL, CHANGE date date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844A7014910 FOREIGN KEY (facility_id) REFERENCES facility (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FE38F844A7014910 ON appointment (facility_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844A76ED395 ON appointment (user_id)');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66CB4833CC');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66CB4833CC FOREIGN KEY (articleCategory_id) REFERENCES article_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emergency CHANGE title title VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE title title VARCHAR(30) NOT NULL, CHANGE location location VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE facility ADD phone BIGINT NOT NULL, ADD fax BIGINT DEFAULT NULL, ADD status TINYINT(1) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD start_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE rank rank INT DEFAULT NULL, CHANGE address location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD64F6BD66');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD64F6BD66 FOREIGN KEY (productCategory_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA371F7E88B');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0D904784C');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A7014910');
        $this->addSql('DROP TABLE donation');
        $this->addSql('DROP TABLE planning');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844A7014910');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844A76ED395');
        $this->addSql('DROP INDEX IDX_FE38F844A7014910 ON appointment');
        $this->addSql('DROP INDEX IDX_FE38F844A76ED395 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP facility_id, DROP user_id, DROP description, CHANGE date date DATETIME NOT NULL, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66CB4833CC');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66CB4833CC FOREIGN KEY (articleCategory_id) REFERENCES article_category (id)');
        $this->addSql('ALTER TABLE emergency CHANGE title title VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE title title VARCHAR(255) NOT NULL, CHANGE location location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facility ADD address VARCHAR(255) NOT NULL, DROP location, DROP phone, DROP fax, DROP status, DROP description, DROP start_time, DROP end_time, CHANGE rank rank VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD64F6BD66');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD64F6BD66 FOREIGN KEY (productCategory_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA371F7E88B');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }
}
