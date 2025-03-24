<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319193016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crear las tablas coche y usuario';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coche (id INT AUTO_INCREMENT NOT NULL, marca VARCHAR(255) NOT NULL, modelo VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, precio DOUBLE PRECISION NOT NULL, kilometros INT NOT NULL, ciudad VARCHAR(255) NOT NULL, carroceria VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, cambio VARCHAR(255) NOT NULL, combustible VARCHAR(255) NOT NULL, traccion VARCHAR(255) NOT NULL, potencia INT NOT NULL, cilindrada INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, contrasena VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE coche');
        $this->addSql('DROP TABLE usuario');
    }
}
