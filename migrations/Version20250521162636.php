<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521162636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coches_images (id INT AUTO_INCREMENT NOT NULL, coche_id_id INT DEFAULT NULL, ruta_imagen VARCHAR(255) DEFAULT NULL, posicion INT DEFAULT NULL, INDEX IDX_5DE663D9798DA86D (coche_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coches_images ADD CONSTRAINT FK_5DE663D9798DA86D FOREIGN KEY (coche_id_id) REFERENCES coche (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coches_images DROP FOREIGN KEY FK_5DE663D9798DA86D');
        $this->addSql('DROP TABLE coches_images');
    }
}
