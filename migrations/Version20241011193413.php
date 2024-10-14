<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241011193413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee_achievement ADD employee_id INT NOT NULL');
        $this->addSql('ALTER TABLE employee_achievement ADD CONSTRAINT FK_1755BE8C8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_1755BE8C8C03F15C ON employee_achievement (employee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee_achievement DROP FOREIGN KEY FK_1755BE8C8C03F15C');
        $this->addSql('DROP INDEX IDX_1755BE8C8C03F15C ON employee_achievement');
        $this->addSql('ALTER TABLE employee_achievement DROP employee_id');
    }
}
