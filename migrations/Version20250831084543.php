<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831084543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE otp_code (id INT AUTO_INCREMENT NOT NULL, token_id INT NOT NULL, user_id INT NOT NULL, code VARCHAR(31) NOT NULL, expiration_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_validated TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_93FE231941DEE7B9 (token_id), UNIQUE INDEX UNIQ_93FE2319A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE otp_code ADD CONSTRAINT FK_93FE231941DEE7B9 FOREIGN KEY (token_id) REFERENCES access_token (id)');
        $this->addSql('ALTER TABLE otp_code ADD CONSTRAINT FK_93FE2319A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE otp_code DROP FOREIGN KEY FK_93FE231941DEE7B9');
        $this->addSql('ALTER TABLE otp_code DROP FOREIGN KEY FK_93FE2319A76ED395');
        $this->addSql('DROP TABLE otp_code');
    }
}
