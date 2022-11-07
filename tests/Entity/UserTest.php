<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase{

    //Ici on crée la function pour set l'entity
    public function getEntity(): User
    {
        return (new User())
            ->setUsername('TestUnit')
            ->setEmail('test@test.fr')
            ->setPassword('000000');
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
    public function testInvalidName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        $user->setUsername('');

        $error = $container->get('validator')->validate($user);
        //on s'attend un error
        $this->assertCount(1, $error);
    }

    //Ici on teste si le mail est blank
    public function testInvalidMail()
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        $user->setEmail('');

        $error = $container->get('validator')->validate($user);
        //on s'attend un error
        $this->assertCount(1, $error);
    }


}