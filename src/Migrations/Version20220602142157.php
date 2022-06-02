<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220602142157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE odiseo_referrals_program (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, product_id INT NOT NULL, order_id INT DEFAULT NULL, token_value VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F7D36D4DBEA95C75 (token_value), INDEX IDX_F7D36D4D9395C3F3 (customer_id), INDEX IDX_F7D36D4D4584665A (product_id), INDEX IDX_F7D36D4D8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odiseo_referrals_program_view (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, referrals_program_id INT NOT NULL, ip VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1EC594A09395C3F3 (customer_id), INDEX IDX_1EC594A027048BCF (referrals_program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odiseo_referrals_program ADD CONSTRAINT FK_F7D36D4D9395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_referrals_program ADD CONSTRAINT FK_F7D36D4D4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_referrals_program ADD CONSTRAINT FK_F7D36D4D8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_referrals_program_view ADD CONSTRAINT FK_1EC594A09395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE odiseo_referrals_program_view ADD CONSTRAINT FK_1EC594A027048BCF FOREIGN KEY (referrals_program_id) REFERENCES odiseo_referrals_program (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE odiseo_referrals_program_view DROP FOREIGN KEY FK_1EC594A027048BCF');
        $this->addSql('DROP TABLE odiseo_referrals_program');
        $this->addSql('DROP TABLE odiseo_referrals_program_view');
    }
}
