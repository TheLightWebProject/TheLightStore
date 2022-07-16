<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220715123003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, total_price INT NOT NULL, UNIQUE INDEX UNIQ_BA388B79395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_details (id INT AUTO_INCREMENT NOT NULL, cart_id INT DEFAULT NULL, products_id INT DEFAULT NULL, quantity INT NOT NULL, total_price INT NOT NULL, INDEX IDX_89FCC38D1AD5CDBF (cart_id), INDEX IDX_89FCC38D6C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B79395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE cart_details ADD CONSTRAINT FK_89FCC38D1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_details ADD CONSTRAINT FK_89FCC38D6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E21A76ED395');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944584584665A');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944589395C3F3');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944584584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C14584665A');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1CFFE9AD6');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEED766068');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEED766068 FOREIGN KEY (username_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A2ADD6D8C');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A44F5D008');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A44F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_details DROP FOREIGN KEY FK_89FCC38D1AD5CDBF');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_details');
        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E21A76ED395');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944589395C3F3');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944584584665A');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944584584665A FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1CFFE9AD6');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C14584665A');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14584665A FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEED766068');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEED766068 FOREIGN KEY (username_id) REFERENCES customers (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A44F5D008');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A2ADD6D8C');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A44F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
