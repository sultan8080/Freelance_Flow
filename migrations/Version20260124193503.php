<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260124193503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD total_ht NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, ADD total_vat NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE status status VARCHAR(20) DEFAULT \'DRAFT\' NOT NULL, CHANGE total_amount total_amount NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE currency currency VARCHAR(255) DEFAULT \'EUR\' NOT NULL');
        $this->addSql('ALTER TABLE invoice_item ADD vat_rate DOUBLE PRECISION DEFAULT 0 NOT NULL, ADD vat_amount NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, ADD total_ht NUMERIC(12, 2) DEFAULT \'0.00\' NOT NULL, ADD total_ttc NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP total_ht, DROP total_vat, CHANGE status status VARCHAR(255) NOT NULL, CHANGE total_amount total_amount NUMERIC(10, 2) NOT NULL, CHANGE currency currency VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice_item DROP vat_rate, DROP vat_amount, DROP total_ht, DROP total_ttc, CHANGE unit_price unit_price NUMERIC(10, 2) NOT NULL');
    }
}
