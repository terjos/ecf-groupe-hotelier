<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin
            ->setLastName('admin')
            ->setFirstName('admin')
            ->setEmail('admin@example.com')
            ->setPassword('$2y$13$WLID6vr6RPX3IJuL29UCaOFNCQPMEnQ6MwyRCilyU435EYjwoZ0yq')
            ->setRoles(['ROLE_ADMIN'])
            ->setAdress('1 rue de la paix')
            ->setCp('75000')
            ->setCity('Paris');
        $manager->persist($admin);

        $gerant = new User();
        $gerant
            ->setLastName('gerant')
            ->setFirstName('gerant')
            ->setEmail('gerant@example.com')
            ->setPassword('$2y$13$WLID6vr6RPX3IJuL29UCaOFNCQPMEnQ6MwyRCilyU435EYjwoZ0yq')
            ->setRoles(['ROLE_GERANT'])
            ->setAdress('1 rue de la paix')
            ->setCp('75000')
            ->setCity('Paris');
        $manager->persist($gerant);

        $client = new User();
        $client
            ->setLastName('client')
            ->setFirstName('client')
            ->setEmail('client@example.com')
            ->setPassword('$2y$13$VOabkPyp0SHe/MnCxG1wFu7n8lRBwSb4uIfXP.NrJbvZqSHjMBJfe')
            ->setRoles(['ROLE_CLIENT'])
            ->setAdress('1 rue de la paix')
            ->setCp('75000')
            ->setCity('Paris');
        $manager->persist($client);

        $manager->flush();
    }
}
