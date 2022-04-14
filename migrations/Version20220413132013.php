<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413132013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room CHANGE featured_image_name featured_image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP adress, DROP cp, DROP city');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room CHANGE featured_image_name featured_image_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD adress VARCHAR(100) NOT NULL, ADD cp VARCHAR(30) NOT NULL, ADD city VARCHAR(50) NOT NULL');
    }
}
