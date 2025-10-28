<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028150341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE coupon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tax_rate_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE coupons_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tax_rates_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE coupons (id INT NOT NULL, code VARCHAR(50) NOT NULL, type VARCHAR(255) NOT NULL, value NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE products (id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tax_rates (id INT NOT NULL, country_code VARCHAR(2) NOT NULL, rate NUMERIC(5, 2) NOT NULL, effective_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, effective_until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN tax_rates.effective_from IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tax_rates.effective_until IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE coupons_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tax_rates_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE coupon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tax_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE coupons');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE tax_rates');
    }
}
