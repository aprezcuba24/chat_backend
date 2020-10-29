<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201029232618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bot (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, webhook VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE channel (id INT AUTO_INCREMENT NOT NULL, workspace_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_A2F98E4782D40A1F (workspace_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE channel_user (channel_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_11C7753772F5A1AA (channel_id), INDEX IDX_11C77537A76ED395 (user_id), PRIMARY KEY(channel_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, channel_id INT NOT NULL, owner_id INT NOT NULL, body LONGTEXT NOT NULL, INDEX IDX_B6BD307F72F5A1AA (channel_id), INDEX IDX_B6BD307F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workspace (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workspace_user (workspace_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C971A58B82D40A1F (workspace_id), INDEX IDX_C971A58BA76ED395 (user_id), PRIMARY KEY(workspace_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E4782D40A1F FOREIGN KEY (workspace_id) REFERENCES workspace (id)');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C7753772F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C77537A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE workspace_user ADD CONSTRAINT FK_C971A58B82D40A1F FOREIGN KEY (workspace_id) REFERENCES workspace (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workspace_user ADD CONSTRAINT FK_C971A58BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel_user DROP FOREIGN KEY FK_11C7753772F5A1AA');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F72F5A1AA');
        $this->addSql('ALTER TABLE channel_user DROP FOREIGN KEY FK_11C77537A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F7E3C61F9');
        $this->addSql('ALTER TABLE workspace_user DROP FOREIGN KEY FK_C971A58BA76ED395');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E4782D40A1F');
        $this->addSql('ALTER TABLE workspace_user DROP FOREIGN KEY FK_C971A58B82D40A1F');
        $this->addSql('DROP TABLE bot');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE channel_user');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE workspace');
        $this->addSql('DROP TABLE workspace_user');
    }
}
