<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230716161438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE airdrop CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE airdrop_recipient CHANGE claimed claimed DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE member CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE recipient CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE airdrop CHANGE created created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE airdrop_recipient CHANGE claimed claimed DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE member CHANGE created created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE recipient CHANGE created created DATETIME NOT NULL');
    }
}
