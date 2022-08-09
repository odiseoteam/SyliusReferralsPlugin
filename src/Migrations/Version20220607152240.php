<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220607152240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE odiseo_affiliate (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, product_id INT DEFAULT NULL, token_value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, expires_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_3256F409BEA95C75 (token_value), INDEX IDX_3256F4099395C3F3 (customer_id), INDEX IDX_3256F4094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odiseo_affiliate_view (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, affiliate_id INT NOT NULL, ip VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_228D3E679395C3F3 (customer_id), INDEX IDX_228D3E679F12C49A (affiliate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odiseo_affiliate ADD CONSTRAINT FK_3256F4099395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_affiliate ADD CONSTRAINT FK_3256F4094584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_affiliate_view ADD CONSTRAINT FK_228D3E679395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_affiliate_view ADD CONSTRAINT FK_228D3E679F12C49A FOREIGN KEY (affiliate_id) REFERENCES odiseo_affiliate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_order ADD affiliate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_order ADD CONSTRAINT FK_6196A1F99F12C49A FOREIGN KEY (affiliate_id) REFERENCES odiseo_affiliate (id)');
        $this->addSql('CREATE INDEX IDX_6196A1F99F12C49A ON sylius_order (affiliate_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE odiseo_affiliate_view DROP FOREIGN KEY FK_228D3E679F12C49A');
        $this->addSql('ALTER TABLE sylius_order DROP FOREIGN KEY FK_6196A1F99F12C49A');
        $this->addSql('DROP TABLE odiseo_affiliate');
        $this->addSql('DROP TABLE odiseo_affiliate_view');
        $this->addSql('DROP INDEX IDX_6196A1F99F12C49A ON sylius_order');
        $this->addSql('ALTER TABLE sylius_order DROP affiliate_id');
    }
}
