<?php

namespace App\Tests;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FixturesTest extends WebTestCase
{

    //Demarer les fixtures
    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        /*$this->databaseTool->loadFixtures([
            'App\DataFixtures\AppFixturesTest'
        ]);*/
    }

    public function testIndex()
    {
        // If you need a client, you must create it before loading fixtures because
        // creating the client boots the kernel, which is used by loadFixtures
        //$client = $this->createClient();

        // add all your fixtures classes that implement
        // Doctrine\Common\DataFixtures\FixtureInterface
        $this->databaseTool->loadFixtures([
            'App\DataFixtures\AppFixturesTest'
        ]);

        // you can now run your functional tests with a populated database
        // ...
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}