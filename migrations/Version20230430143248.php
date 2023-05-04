<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430143248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE basket (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', checkout VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2246507BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_product (id INT AUTO_INCREMENT NOT NULL, basket_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_2890CCAA1BE1FB52 (basket_id), INDEX IDX_2890CCAA4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, rating DOUBLE PRECISION NOT NULL, productCategory_id INT DEFAULT NULL, INDEX IDX_D34A04AD64F6BD66 (productCategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_CDFC7356A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD64F6BD66 FOREIGN KEY (productCategory_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC7356A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE emergency DROP FOREIGN KEY FK_26DD111DA76ED395');
        $this->addSql('ALTER TABLE emergency CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE emergency ADD CONSTRAINT FK_26DD111DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507BA76ED395');
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA1BE1FB52');
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA4584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD64F6BD66');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC7356A76ED395');
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE cart_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('ALTER TABLE emergency DROP FOREIGN KEY FK_26DD111DA76ED395');
        $this->addSql('ALTER TABLE emergency CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE emergency ADD CONSTRAINT FK_26DD111DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
