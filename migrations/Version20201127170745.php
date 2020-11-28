<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127170745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE channel_bot (channel_id INT NOT NULL, bot_id INT NOT NULL, INDEX IDX_5CBAD3D572F5A1AA (channel_id), INDEX IDX_5CBAD3D592C1C487 (bot_id), PRIMARY KEY(channel_id, bot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE channel_bot ADD CONSTRAINT FK_5CBAD3D572F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE channel_bot ADD CONSTRAINT FK_5CBAD3D592C1C487 FOREIGN KEY (bot_id) REFERENCES bot (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE channel_bot');
    }
}
