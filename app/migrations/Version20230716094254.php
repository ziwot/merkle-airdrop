<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230716094254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE airdrop (id INT AUTO_INCREMENT NOT NULL, token_id INT NOT NULL, merkle_root VARCHAR(64) DEFAULT NULL, address VARCHAR(36) DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_4D15AF2C41DEE7B9 (token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE airdrop_recipient (id INT AUTO_INCREMENT NOT NULL, recipient_id INT NOT NULL, airdrop_id INT NOT NULL, amount INT NOT NULL, claimed DATETIME DEFAULT NULL, INDEX IDX_5A63DFC1E92F8F78 (recipient_id), INDEX IDX_5A63DFC113543E34 (airdrop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(36) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipient (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(36) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, network VARCHAR(24) NOT NULL, address VARCHAR(36) NOT NULL, identifier INT NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE airdrop ADD CONSTRAINT FK_4D15AF2C41DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id)');
        $this->addSql('ALTER TABLE airdrop_recipient ADD CONSTRAINT FK_5A63DFC1E92F8F78 FOREIGN KEY (recipient_id) REFERENCES recipient (id)');
        $this->addSql('ALTER TABLE airdrop_recipient ADD CONSTRAINT FK_5A63DFC113543E34 FOREIGN KEY (airdrop_id) REFERENCES airdrop (id)');
        $this->addSql('ALTER TABLE airdrops DROP FOREIGN KEY airdrops_ibfk_1');
        $this->addSql('ALTER TABLE airdrops_recipients DROP FOREIGN KEY FK_9EB53DD7A76ED395');
        $this->addSql('ALTER TABLE airdrops_recipients DROP FOREIGN KEY FK_9EB53DD713543E34');
        $this->addSql('DROP TABLE airdrops');
        $this->addSql('DROP TABLE airdrops_recipients');
        $this->addSql('DROP TABLE recipients');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('DROP TABLE users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE airdrops (id INT AUTO_INCREMENT NOT NULL, token_id INT NOT NULL, merkle_root VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, address VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT NULL, INDEX airdrops_ibfk_1 (token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE airdrops_recipients (id INT AUTO_INCREMENT NOT NULL, airdrop_id INT NOT NULL, recipient_id INT NOT NULL, amount INT NOT NULL, claimed DATETIME DEFAULT NULL, INDEX FK_9EB53DD713543E34 (airdrop_id), INDEX FK_9EB53DD7A76ED395 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE recipients (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tokens (id INT AUTO_INCREMENT NOT NULL, network VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, address VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, identifier INT NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT NULL, UNIQUE INDEX pkh (address), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE airdrops ADD CONSTRAINT airdrops_ibfk_1 FOREIGN KEY (token_id) REFERENCES tokens (id)');
        $this->addSql('ALTER TABLE airdrops_recipients ADD CONSTRAINT FK_9EB53DD7A76ED395 FOREIGN KEY (recipient_id) REFERENCES recipients (id)');
        $this->addSql('ALTER TABLE airdrops_recipients ADD CONSTRAINT FK_9EB53DD713543E34 FOREIGN KEY (airdrop_id) REFERENCES airdrops (id)');
        $this->addSql('ALTER TABLE airdrop DROP FOREIGN KEY FK_4D15AF2C41DEE7B9');
        $this->addSql('ALTER TABLE airdrop_recipient DROP FOREIGN KEY FK_5A63DFC1E92F8F78');
        $this->addSql('ALTER TABLE airdrop_recipient DROP FOREIGN KEY FK_5A63DFC113543E34');
        $this->addSql('DROP TABLE airdrop');
        $this->addSql('DROP TABLE airdrop_recipient');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE recipient');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
