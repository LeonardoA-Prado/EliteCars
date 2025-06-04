<?php

namespace App\DataFixtures;

use App\Entity\Marcas;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MarcasFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $marcas = [
            'Chevrolet',
            'Ford',
            'Toyota',
            'Honda',
            'BMW',
            'Mercedes-Benz',
            'Audi',
            'Volkswagen',
            'Nissan',
            'Hyundai'
        ];

        foreach ($marcas as $nombre) {
            $marca = new Marcas();
            $marca->setNombre($nombre);
            $manager->persist($marca);
        }

        $manager->flush();
    }
}
