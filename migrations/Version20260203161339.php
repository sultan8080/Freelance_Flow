<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260203161339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, company_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, siret VARCHAR(14) DEFAULT NULL, vat_number VARCHAR(20) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, post_code VARCHAR(10) DEFAULT NULL, country VARCHAR(100) DEFAULT \'France\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_C7440455A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, invoice_number VARCHAR(255) DEFAULT NULL, due_date DATETIME DEFAULT NULL, status VARCHAR(20) DEFAULT \'DRAFT\' NOT NULL, sent_at DATETIME DEFAULT NULL, paid_at DATETIME DEFAULT NULL, total_ht NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, total_vat NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, total_amount NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, currency VARCHAR(255) DEFAULT \'EUR\' NOT NULL, project_title VARCHAR(255) DEFAULT NULL, frozen_client_name VARCHAR(255) DEFAULT NULL, frozen_client_address VARCHAR(255) DEFAULT NULL, frozen_client_postcode VARCHAR(20) DEFAULT NULL, frozen_client_city VARCHAR(100) DEFAULT NULL, frozen_client_country VARCHAR(100) DEFAULT NULL, frozen_client_phone VARCHAR(20) DEFAULT NULL, frozen_client_email VARCHAR(255) DEFAULT NULL, frozen_client_siret VARCHAR(255) DEFAULT NULL, frozen_client_vat VARCHAR(255) DEFAULT NULL, frozen_client_company_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, client_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9065174419EB6921 (client_id), INDEX IDX_90651744A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE invoice_item (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, quantity NUMERIC(10, 2) NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, vat_rate NUMERIC(5, 2) NOT NULL, total_ht NUMERIC(10, 2) DEFAULT NULL, vat_amount NUMERIC(10, 2) DEFAULT NULL, total_ttc NUMERIC(10, 2) DEFAULT NULL, invoice_id INT NOT NULL, INDEX IDX_1DDE477B2989F1FD (invoice_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, address LONGTEXT DEFAULT NULL, siret_number VARCHAR(255) DEFAULT NULL, vat_number VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, post_code VARCHAR(20) DEFAULT NULL, city VARCHAR(150) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6493D05145B (siret_number), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_9065174419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invoice_item ADD CONSTRAINT FK_1DDE477B2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_9065174419EB6921');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744A76ED395');
        $this->addSql('ALTER TABLE invoice_item DROP FOREIGN KEY FK_1DDE477B2989F1FD');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_item');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
