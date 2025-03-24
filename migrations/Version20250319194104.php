<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319194104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coche (id INT AUTO_INCREMENT NOT NULL, marca VARCHAR(255) NOT NULL, modelo VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, precio DOUBLE PRECISION NOT NULL, kilometros INT NOT NULL, ciudad VARCHAR(255) NOT NULL, carroceria VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, cambio VARCHAR(255) NOT NULL, combustible VARCHAR(255) NOT NULL, traccion VARCHAR(255) NOT NULL, potencia INT NOT NULL, cilindrada INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaccion (id INT AUTO_INCREMENT NOT NULL, coche_id INT DEFAULT NULL, comprador_id INT DEFAULT NULL, vendedor_id INT DEFAULT NULL, fecha_transaccion DATE NOT NULL, precio_transaccion DOUBLE PRECISION NOT NULL, INDEX IDX_BFF96AF7F4621E56 (coche_id), INDEX IDX_BFF96AF7200A5E25 (comprador_id), INDEX IDX_BFF96AF78361A8B8 (vendedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, contrasena VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaccion ADD CONSTRAINT FK_BFF96AF7F4621E56 FOREIGN KEY (coche_id) REFERENCES coche (id)');
        $this->addSql('ALTER TABLE transaccion ADD CONSTRAINT FK_BFF96AF7200A5E25 FOREIGN KEY (comprador_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE transaccion ADD CONSTRAINT FK_BFF96AF78361A8B8 FOREIGN KEY (vendedor_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaccion DROP FOREIGN KEY FK_BFF96AF7F4621E56');
        $this->addSql('ALTER TABLE transaccion DROP FOREIGN KEY FK_BFF96AF7200A5E25');
        $this->addSql('ALTER TABLE transaccion DROP FOREIGN KEY FK_BFF96AF78361A8B8');
        $this->addSql('DROP TABLE coche');
        $this->addSql('DROP TABLE transaccion');
        $this->addSql('DROP TABLE usuario');
    }
}
