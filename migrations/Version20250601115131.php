<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601115131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coche ADD marca_id INT DEFAULT NULL, ADD combustible_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE coche ADD CONSTRAINT FK_A1981CD481EF0041 FOREIGN KEY (marca_id) REFERENCES marcas (id)');
        $this->addSql('ALTER TABLE coche ADD CONSTRAINT FK_A1981CD4D5BD96DF FOREIGN KEY (combustible_id) REFERENCES combustible (id)');
        $this->addSql('CREATE INDEX IDX_A1981CD481EF0041 ON coche (marca_id)');
        $this->addSql('CREATE INDEX IDX_A1981CD4D5BD96DF ON coche (combustible_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coche DROP FOREIGN KEY FK_A1981CD481EF0041');
        $this->addSql('ALTER TABLE coche DROP FOREIGN KEY FK_A1981CD4D5BD96DF');
        $this->addSql('DROP INDEX IDX_A1981CD481EF0041 ON coche');
        $this->addSql('DROP INDEX IDX_A1981CD4D5BD96DF ON coche');
        $this->addSql('ALTER TABLE coche DROP marca_id, DROP combustible_id');
    }
}
