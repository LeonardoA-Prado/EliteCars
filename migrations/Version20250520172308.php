<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520172308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coche ADD vendedor_id INT DEFAULT NULL, ADD ruta_img VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE coche ADD CONSTRAINT FK_A1981CD48361A8B8 FOREIGN KEY (vendedor_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_A1981CD48361A8B8 ON coche (vendedor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coche DROP FOREIGN KEY FK_A1981CD48361A8B8');
        $this->addSql('DROP INDEX IDX_A1981CD48361A8B8 ON coche');
        $this->addSql('ALTER TABLE coche DROP vendedor_id, DROP ruta_img');
    }
}
