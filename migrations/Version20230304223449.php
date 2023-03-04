<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304223449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507B8D9F6D38');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993981BE1FB52');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP INDEX UNIQ_2246507B8D9F6D38 ON basket');
        $this->addSql('ALTER TABLE basket ADD user_id INT DEFAULT NULL, ADD checkout VARCHAR(255) NOT NULL, DROP order_id');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2246507BA76ED395 ON basket (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, basket_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, checkout_status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F52993981BE1FB52 (basket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993981BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507BA76ED395');
        $this->addSql('DROP INDEX UNIQ_2246507BA76ED395 ON basket');
        $this->addSql('ALTER TABLE basket ADD order_id INT NOT NULL, DROP user_id, DROP checkout');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507B8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2246507B8D9F6D38 ON basket (order_id)');
    }
}
