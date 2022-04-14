<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin
            ->setLastName('admin')
            ->setFirstName('admin')
            ->setEmail('admin@example.com')
            ->setPassword('$2y$13$WLID6vr6RPX3IJuL29UCaOFNCQPMEnQ6MwyRCilyU435EYjwoZ0yq')
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $gerant = new User();
        $gerant
            ->setLastName('gerant')
            ->setFirstName('gerant')
            ->setEmail('gerant@example.com')
            ->setPassword('$2y$13$WLID6vr6RPX3IJuL29UCaOFNCQPMEnQ6MwyRCilyU435EYjwoZ0yq')
            ->setRoles(['ROLE_GERANT'])
            ->setEstablishment($this->getReference('establishment1'));

        $manager->persist($gerant);

        $client = new User();
        $client
            ->setLastName('client')
            ->setFirstName('client')
            ->setEmail('client@example.com')
            ->setPassword('$2y$13$paC/lq0zl14VDO03JfIdCewljUmRBK4p4r6QpNF2jWzpD7AXHF4Xy')
            ->setRoles(['ROLE_CLIENT']);
        $manager->persist($client);

        $this->addReference('client', $client);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            EstablishmentFixtures::class,
        );
    }
}
