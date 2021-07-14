<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713110936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE used_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, owner_id INT NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, capacity DOUBLE PRECISION DEFAULT NULL, expiration DATE DEFAULT NULL, INDEX IDX_549D215C4584665A (product_id), INDEX IDX_549D215C7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE used_product ADD CONSTRAINT FK_549D215C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE used_product ADD CONSTRAINT FK_549D215C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `group` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE used_product');
    }
}
