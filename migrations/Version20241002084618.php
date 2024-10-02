<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241002084618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD random VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, ADD password VARCHAR(255) NOT NULL, DROP nom, DROP prenom, DROP email, DROP mdp, DROP image');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_RANDOM ON user (random)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_RANDOM ON user');
        $this->addSql('ALTER TABLE user ADD prenom VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD mdp INT NOT NULL, ADD image INT NOT NULL, DROP random, DROP roles, CHANGE password nom VARCHAR(255) NOT NULL');
    }
}
