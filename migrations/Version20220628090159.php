<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220628090159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E21A76ED395');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944589395C3F3');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1CFFE9AD6');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEED766068');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEED766068 FOREIGN KEY (username_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A44F5D008');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A44F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id)');
        $this->addSql('ALTER TABLE user ADD is_verify TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E21A76ED395');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944589395C3F3');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1CFFE9AD6');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEED766068');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEED766068 FOREIGN KEY (username_id) REFERENCES customers (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A44F5D008');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A44F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP is_verify');
    }
}
