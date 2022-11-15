<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase{

    //Ici on crée la function pour set l'entity
    public function getEntity(): User
    {
        return (new User())
            ->setUsername('TestUnit')
            ->setEmail('testo@test.fr')
            ->setPassword('000000000');
    }

    //ici on test que les données entre correctement
    public function testValidEntity()
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();

        $error = $container->get('validator')->validate($user);
        //on s'attend 0 error
        $this->assertCount(0, $error);
    }

    //Ici on teste si le username est blank
    public function testInvalidBlank()
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        //test notblank
        $user->setUsername('');
        $user->setEmail('');


        $error = $container->get('validator')->validate($user);
        //on s'attend un error
        $this->assertCount(2, $error);
    }

    //Ici on teste si le username correspond pas aux accert
    public function testInvalidNumberOrMail()
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();

        //test chifres username et mail sans structure mail
        $user->setUsername('Test123');
        $user->setEmail('test');

        $error = $container->get('validator')->validate($user);
        //on s'attend un error
        $this->assertCount(2, $error);
    }






}