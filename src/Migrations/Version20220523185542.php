<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220523185542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE odiseo_customer_payment (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, currency_code VARCHAR(3) NOT NULL, amount INT NOT NULL, state VARCHAR(255) NOT NULL, details JSON NOT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F1FCE3578D9F6D38 (order_id), INDEX IDX_F1FCE3579395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odiseo_referrals_program (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, product_id INT DEFAULT NULL, token_value VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, expire_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F7D36D4D9395C3F3 (customer_id), INDEX IDX_F7D36D4D4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odiseo_referrals_program_payments (referralsprogram_id INT NOT NULL, customerpayment_id INT NOT NULL, INDEX IDX_BD9DBF11A39E1281 (referralsprogram_id), UNIQUE INDEX UNIQ_BD9DBF11E2A2FABC (customerpayment_id), PRIMARY KEY(referralsprogram_id, customerpayment_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odiseo_referrals_program_view (id INT AUTO_INCREMENT NOT NULL, referrals_program_id INT NOT NULL, customer_id INT DEFAULT NULL, ip VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1EC594A027048BCF (referrals_program_id), INDEX IDX_1EC594A09395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odiseo_customer_payment ADD CONSTRAINT FK_F1FCE3578D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('ALTER TABLE odiseo_customer_payment ADD CONSTRAINT FK_F1FCE3579395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id)');
        $this->addSql('ALTER TABLE odiseo_referrals_program ADD CONSTRAINT FK_F7D36D4D9395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id)');
        $this->addSql('ALTER TABLE odiseo_referrals_program ADD CONSTRAINT FK_F7D36D4D4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE odiseo_referrals_program_payments ADD CONSTRAINT FK_BD9DBF11A39E1281 FOREIGN KEY (referralsprogram_id) REFERENCES odiseo_referrals_program (id)');
        $this->addSql('ALTER TABLE odiseo_referrals_program_payments ADD CONSTRAINT FK_BD9DBF11E2A2FABC FOREIGN KEY (customerpayment_id) REFERENCES odiseo_customer_payment (id)');
        $this->addSql('ALTER TABLE odiseo_referrals_program_view ADD CONSTRAINT FK_1EC594A027048BCF FOREIGN KEY (referrals_program_id) REFERENCES odiseo_referrals_program (id)');
        $this->addSql('ALTER TABLE odiseo_referrals_program_view ADD CONSTRAINT FK_1EC594A09395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE odiseo_referrals_program_payments DROP FOREIGN KEY FK_BD9DBF11E2A2FABC');
        $this->addSql('ALTER TABLE odiseo_referrals_program_payments DROP FOREIGN KEY FK_BD9DBF11A39E1281');
        $this->addSql('ALTER TABLE odiseo_referrals_program_view DROP FOREIGN KEY FK_1EC594A027048BCF');
        $this->addSql('DROP TABLE odiseo_customer_payment');
        $this->addSql('DROP TABLE odiseo_referrals_program');
        $this->addSql('DROP TABLE odiseo_referrals_program_payments');
        $this->addSql('DROP TABLE odiseo_referrals_program_view');
    }
}
