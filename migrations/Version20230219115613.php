<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230219115613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_product (id INT AUTO_INCREMENT NOT NULL, basket_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_2890CCAA1BE1FB52 (basket_id), INDEX IDX_2890CCAA4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE baskets_products DROP FOREIGN KEY FK_4EAC81BF1BE1FB52');
        $this->addSql('ALTER TABLE baskets_products DROP FOREIGN KEY FK_4EAC81BF4584665A');
        $this->addSql('DROP TABLE baskets_products');
        $this->addSql('ALTER TABLE basket ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP total_number, CHANGE total_purchase total DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product_category ADD description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE baskets_products (product_id INT NOT NULL, basket_id INT NOT NULL, INDEX IDX_4EAC81BF4584665A (product_id), INDEX IDX_4EAC81BF1BE1FB52 (basket_id), PRIMARY KEY(product_id, basket_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE baskets_products ADD CONSTRAINT FK_4EAC81BF1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE baskets_products ADD CONSTRAINT FK_4EAC81BF4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA1BE1FB52');
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA4584665A');
        $this->addSql('DROP TABLE cart_product');
        $this->addSql('ALTER TABLE basket ADD total_number INT NOT NULL, DROP created_at, DROP updated_at, CHANGE total total_purchase DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product_category DROP description');
    }
}
