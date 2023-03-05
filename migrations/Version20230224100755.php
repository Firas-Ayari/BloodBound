<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224100755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE baskets_products (product_id INT NOT NULL, basket_id INT NOT NULL, INDEX IDX_4EAC81BF4584665A (product_id), INDEX IDX_4EAC81BF1BE1FB52 (basket_id), PRIMARY KEY(product_id, basket_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE baskets_products ADD CONSTRAINT FK_4EAC81BF4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE baskets_products ADD CONSTRAINT FK_4EAC81BF1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA4584665A');
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA1BE1FB52');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0D904784C');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A7014910');
        $this->addSql('DROP TABLE cart_product');
        $this->addSql('DROP TABLE donation');
        $this->addSql('DROP TABLE planning');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844A7014910');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844A76ED395');
        $this->addSql('DROP INDEX IDX_FE38F844A7014910 ON appointment');
        $this->addSql('DROP INDEX IDX_FE38F844A76ED395 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP facility_id, DROP user_id, DROP description, CHANGE date date DATETIME NOT NULL, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE basket ADD total_number INT NOT NULL, DROP created_at, DROP updated_at, CHANGE total total_purchase DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE emergency CHANGE title title VARCHAR(255) NOT NULL, CHANGE blood_type blood_type VARCHAR(255) NOT NULL, CHANGE location location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE title title VARCHAR(255) NOT NULL, CHANGE location location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facility ADD address VARCHAR(255) NOT NULL, DROP location, DROP phone, DROP fax, DROP status, DROP description, DROP start_time, DROP end_time, CHANGE rank rank VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD64F6BD66');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD64F6BD66 FOREIGN KEY (productCategory_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product_category DROP description');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA371F7E88B');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE user DROP is_verified, DROP created_at, DROP updated_at, CHANGE roles roles VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_product (id INT AUTO_INCREMENT NOT NULL, basket_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_2890CCAA1BE1FB52 (basket_id), INDEX IDX_2890CCAA4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE donation (id INT AUTO_INCREMENT NOT NULL, emergency_id INT DEFAULT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, don_location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, phone_number BIGINT NOT NULL, donation_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_31E581A0D904784C (emergency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, facility_id INT DEFAULT NULL, weekday DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', hourtime TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', INDEX IDX_D499BFF6A7014910 (facility_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA1BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0D904784C FOREIGN KEY (emergency_id) REFERENCES emergency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A7014910 FOREIGN KEY (facility_id) REFERENCES facility (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('ALTER TABLE baskets_products DROP FOREIGN KEY FK_4EAC81BF4584665A');
        $this->addSql('ALTER TABLE baskets_products DROP FOREIGN KEY FK_4EAC81BF1BE1FB52');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE baskets_products');
        $this->addSql('ALTER TABLE appointment ADD facility_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD description VARCHAR(255) NOT NULL, CHANGE date date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844A7014910 FOREIGN KEY (facility_id) REFERENCES facility (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FE38F844A7014910 ON appointment (facility_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844A76ED395 ON appointment (user_id)');
        $this->addSql('ALTER TABLE basket ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP total_number, CHANGE total_purchase total DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE emergency CHANGE title title VARCHAR(30) NOT NULL, CHANGE blood_type blood_type VARCHAR(5) NOT NULL, CHANGE location location VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE title title VARCHAR(30) NOT NULL, CHANGE location location VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE facility ADD phone BIGINT NOT NULL, ADD fax BIGINT DEFAULT NULL, ADD status TINYINT(1) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD start_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE rank rank INT DEFAULT NULL, CHANGE address location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD64F6BD66');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD64F6BD66 FOREIGN KEY (productCategory_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA371F7E88B');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user` ADD is_verified TINYINT(1) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
