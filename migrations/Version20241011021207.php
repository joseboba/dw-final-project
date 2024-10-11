<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241011021207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee_achievement (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, achievement_type TINYINT(1) NOT NULL, achievement_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee ADD position_id INT NOT NULL, ADD store_id INT NOT NULL, ADD salary NUMERIC(18, 2) NOT NULL');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1DD842E46 FOREIGN KEY (position_id) REFERENCES positions (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A1DD842E46 ON employee (position_id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A1B092A811 ON employee (store_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE employee_achievement');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1DD842E46');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1B092A811');
        $this->addSql('DROP INDEX IDX_5D9F75A1DD842E46 ON employee');
        $this->addSql('DROP INDEX IDX_5D9F75A1B092A811 ON employee');
        $this->addSql('ALTER TABLE employee DROP position_id, DROP store_id, DROP salary');
    }
}
