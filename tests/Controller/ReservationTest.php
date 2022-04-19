<?php

namespace App\Tests\Controller;

use App\DataFixtures\ReservationFixtures;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ReservationControllerTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected KernelBrowser $client;
    protected ReferenceRepository $fixtures;
    protected int $establishmentId;
    protected int $roomId;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->fixtures = $this->databaseTool->loadFixtures([
            ReservationFixtures::class,
        ])->getReferenceRepository();
        $this->establishmentId = $this->fixtures->getReference('establishment0')->getId();
        $this->roomId = $this->fixtures->getReference('room00')->getId();
    }

    public function testRedirectToLogin(): void
    {
        $client = $this->client;
        $client->request('GET', '/reservation/creer');

        $this->assertResponseRedirects('/login');
    }

    public function testSuccessFullLogin(): void
    {
        $client = $this->client;
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connection')->form([
            'email' => 'client@example.com',
            'password' => 'pass',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/etablissement/');
    }

    /*
    * Test the creation of a reservation
    * in DB from 2022-08-01 to 2022-08-04
    * test from 2022-08-02 to 2022-08-03
    */
    public function testErrorAddReservation_1(): void
    {
        $crawler = $this->initFormAddReservation();

        $form = $crawler->selectButton('Enregistrer')->form([
            'reservation[room]' => $this->roomId,
            'reservation[startAt]' => '2022-08-02',
            'reservation[endAt]' => '2022-08-03',
        ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame('422');
    }

    /*
    * Test the creation of a reservation
    * in DB from 2022-08-07 to 2022-08-08
    * test from 2022-08-06 to 2022-08-09
    */
    public function testErrorAddReservation_2(): void
    {
        $crawler = $this->initFormAddReservation();

        $form = $crawler->selectButton('Enregistrer')->form([
            'reservation[room]' => $this->roomId,
            'reservation[startAt]' => '2022-08-06',
            'reservation[endAt]' => '2022-08-09',
        ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame('422');
    }

    /*
    * Test the creation of a reservation
    * in DB from 2022-08-12 to 2022-08-16
    * test from 2022-08-10 to 2022-08-13
    */
    public function testErrorAddReservation_3(): void
    {
        $crawler = $this->initFormAddReservation();

        $form = $crawler->selectButton('Enregistrer')->form([
            'reservation[room]' => $this->roomId,
            'reservation[startAt]' => '2022-08-10',
            'reservation[endAt]' => '2022-08-13',
        ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame('422');
    }

    /*
    * Test the creation of a reservation
    * in DB from 2022-08-12 to 2022-08-16
    * test from 2022-08-15 to 2022-08-17
    */
    public function testErrorAddReservation_4(): void
    {
        $crawler = $this->initFormAddReservation();

        $form = $crawler->selectButton('Enregistrer')->form([
            'reservation[room]' => $this->roomId,
            'reservation[startAt]' => '2022-08-15',
            'reservation[endAt]' => '2022-08-17',
        ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame('422');
    }

    public function testErrorAddReservationEndSmallerStart(): void
    {
        $crawler = $this->initFormAddReservation();

        $form = $crawler->selectButton('Enregistrer')->form([
            'reservation[room]' => $this->roomId,
            'reservation[startAt]' => '2023-08-15',
            'reservation[endAt]' => '2023-08-13',
        ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame('422');
    }

    /*
    * Test the creation of a reservation
    * in DB from 2022-08-23 to 2022-08-25 and from 2022-08-28 to 2022-08-29
    * test from 2022-08-26 to 2022-08-27
    */
    public function testSuccessAddReservation(): void
    {
        $crawler = $this->initFormAddReservation();

        $form = $crawler->selectButton('Enregistrer')->form([
            'reservation[room]' => $this->roomId,
            'reservation[startAt]' => '2022-08-26',
            'reservation[endAt]' => '2022-08-27',
        ]);
        $crawler = $this->client->submit($form);
        $this->assertResponseRedirects('/suite/' . $this->roomId);
    }

    private function initFormAddReservation(): Crawler
    {
        $client = $this->client;

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connection')->form([
            'email' => 'client@example.com',
            'password' => 'pass',
        ]);
        $client->submit($form);


        $crawler = $client->request('GET', '/reservation/creer');
        $form = $crawler->selectButton('Enregistrer')->form([
            'reservation[establishment]' => $this->establishmentId,
        ]);
        $crawler = $client->submit($form);

        return $crawler;
    }
}
