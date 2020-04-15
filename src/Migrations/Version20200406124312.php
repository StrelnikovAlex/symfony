<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406124312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, ticket_id INT NOT NULL, INDEX IDX_9474526C700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF46C1197C9');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF46C1197C9 FOREIGN KEY (project_id_id) REFERENCES projects (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF46C1197C9');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF46C1197C9 FOREIGN KEY (project_id_id) REFERENCES projects (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
