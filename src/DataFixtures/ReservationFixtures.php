<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $resa = new Reservation();
                $resa
                    ->setStartAt(new DateTimeImmutable('2022-01-01'))
                    ->setEndAt(new DateTimeImmutable('2022-01-03'))
                    ->setRoom($this->getReference('room' . $i . $j))
                    ->setUser($this->getReference('client'));

                $manager->persist($resa);
            }
        }
        $dates = [
            ['2022-08-01', '2022-08-04'],
            ['2022-08-07', '2022-08-08'],
            ['2022-08-12', '2022-08-16'],
            ['2022-08-19', '2022-08-21'],
            ['2022-08-23', '2022-08-25'],
            ['2022-08-28', '2022-08-29'],
        ];
        foreach ($dates as $date) {
            $resa = new Reservation();
            $resa
                ->setStartAt(new DateTimeImmutable($date[0]))
                ->setEndAt(new DateTimeImmutable($date[1]))
                ->setRoom($this->getReference('room00'))
                ->setUser($this->getReference('client'));
            $manager->persist($resa);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            RoomFixtures::class,
            UserFixtures::class,
        );
    }
}
