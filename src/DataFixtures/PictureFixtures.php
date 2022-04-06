<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 10; $j++) {
                for ($k = 0; $k < 3; $k++) {
                    $pictureNumber = $faker->numberBetween(1, 18);
                    $picture = new Picture();
                    $picture
                        ->setLabel($faker->name)
                        ->setPictureName("fake-room-$pictureNumber.jpg")
                        ->setRoom($this->getReference('room' . $i . $j));

                    $manager->persist($picture);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            RoomFixtures::class,
        );
    }
}
