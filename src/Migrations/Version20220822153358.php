<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220822153358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE odiseo_affiliate_referral (id INT AUTO_INCREMENT NOT NULL, affiliate_id INT NOT NULL, product_id INT DEFAULT NULL, token_value VARCHAR(255) NOT NULL, reward_type VARCHAR(255) NOT NULL, expires_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_83F20EA0BEA95C75 (token_value), INDEX IDX_83F20EA09F12C49A (affiliate_id), INDEX IDX_83F20EA04584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odiseo_affiliate_referral_view (id INT AUTO_INCREMENT NOT NULL, affiliate_referral_id INT NOT NULL, ip VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_95CE5F06FFF7A55E (affiliate_referral_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odiseo_affiliate_referral ADD CONSTRAINT FK_83F20EA09F12C49A FOREIGN KEY (affiliate_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_affiliate_referral ADD CONSTRAINT FK_83F20EA04584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_affiliate_referral_view ADD CONSTRAINT FK_95CE5F06FFF7A55E FOREIGN KEY (affiliate_referral_id) REFERENCES odiseo_affiliate_referral (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_order ADD affiliate_referral_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_order ADD CONSTRAINT FK_6196A1F9FFF7A55E FOREIGN KEY (affiliate_referral_id) REFERENCES odiseo_affiliate_referral (id)');
        $this->addSql('CREATE INDEX IDX_6196A1F9FFF7A55E ON sylius_order (affiliate_referral_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE odiseo_affiliate_referral_view DROP FOREIGN KEY FK_95CE5F06FFF7A55E');
        $this->addSql('ALTER TABLE sylius_order DROP FOREIGN KEY FK_6196A1F9FFF7A55E');
        $this->addSql('DROP TABLE odiseo_affiliate_referral');
        $this->addSql('DROP TABLE odiseo_affiliate_referral_view');
        $this->addSql('DROP INDEX IDX_6196A1F9FFF7A55E ON sylius_order');
        $this->addSql('ALTER TABLE sylius_order DROP affiliate_referral_id');
    }
}
