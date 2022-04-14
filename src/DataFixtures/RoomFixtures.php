<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class RoomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $room = new Room();
                $room
                    ->setTitle($faker->name())
                    ->setFeaturedImageName('fake-room.jpg')
                    ->setPrice($faker->randomFloat(2, 90, 300))
                    ->setBookingLink('https://www.booking.com/')
                    ->setDescription($faker->text())
                    ->setEstablishment($this->getReference('establishment' . $i));

                $manager->persist($room);

                $this->addReference('room' . $i . $j, $room);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            EstablishmentFixtures::class,
        );
    }
}
