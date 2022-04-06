<?php

namespace App\DataFixtures;

use App\Entity\Establishment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EstablishmentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 7; $i++) {
            $establishment = new Establishment();
            $establishment
                ->setName($faker->company)
                ->setAdress($faker->streetAddress)
                ->setCp($faker->postcode)
                ->setCity($faker->city)
                ->setDescription($faker->text)
                ->setPictureName('fake-establishment.jpg');

            $manager->persist($establishment);

            $this->addReference('establishment' . $i, $establishment);
        }
        $manager->flush();
    }
}
