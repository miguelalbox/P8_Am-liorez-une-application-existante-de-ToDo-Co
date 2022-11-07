<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{

    //Ici on crée la function pour set l'entity
    public function getEntity(): Task
    {
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class, 1);

        return (new Task())
            ->setTitle('TestUnit')
            ->setContent('Contenu')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setIsDone(false)
            ->setUser($user);
    }

    //ici on test que les données entre correctement
    public function testValidEntity()
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = $this->getEntity();

        $error = $container->get('validator')->validate($task);
        //on s'attend 0 error
        $this->assertCount(0, $error);
    }

    //Ici on teste si le title est blank
    public function testInvalidTitle()
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = $this->getEntity();
        $task->setTitle('');

        $error = $container->get('validator')->validate($task);
        //on s'attend un error
        $this->assertCount(1, $error);
    }

    //Ici on teste si le contenu est blank
    public function testInvalidContent()
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = $this->getEntity();
        $task->setContent('');

        $error = $container->get('validator')->validate($task);
        //on s'attend un error
        $this->assertCount(1, $error);
    }
}