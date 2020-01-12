<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200111215907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE currencies (id INT AUTO_INCREMENT NOT NULL, exchange_id VARCHAR(100) DEFAULT NULL, code TINYTEXT NOT NULL, rate DOUBLE PRECISION NOT NULL, name VARCHAR(100) DEFAULT NULL, nominal INT NOT NULL, INDEX IDX_37C4469368AFD1A0 (exchange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exchanges (id VARCHAR(100) NOT NULL, date DATE NOT NULL, base TINYTEXT NOT NULL, last_update_time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE currencies ADD CONSTRAINT FK_37C4469368AFD1A0 FOREIGN KEY (exchange_id) REFERENCES exchanges (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE currencies DROP FOREIGN KEY FK_37C4469368AFD1A0');
        $this->addSql('DROP TABLE currencies');
        $this->addSql('DROP TABLE exchanges');
    }
}
