<?php

namespace App\DataFixtures;

use App\Entity\Combustible;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CombustibleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $combustibles = [
            'Gasolina',
            'Diésel',
            'Eléctrico',
            'Híbrido'
        ];

        foreach ($combustibles as $tipo) {
            $combustible = new Combustible();
            $combustible->setNombre($tipo);
            $manager->persist($combustible);
        }

        $manager->flush();
    }
}
