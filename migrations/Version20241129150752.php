<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241129150752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_token RENAME INDEX uniq_b6a2dd689d86650f TO UNIQ_B6A2DD68A76ED395');
        $this->addSql('ALTER TABLE post DROP url, DROP type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE access_token RENAME INDEX uniq_b6a2dd68a76ed395 TO UNIQ_B6A2DD689D86650F');
        $this->addSql('ALTER TABLE post ADD url VARCHAR(511) NOT NULL, ADD type VARCHAR(255) NOT NULL');
    }
}
