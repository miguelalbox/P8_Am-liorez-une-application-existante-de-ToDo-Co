<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class AppFixturesTest extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Creation d'utilisateurs
        for($i = 0; $i < 13; $i++){
            $user = new User;
            $user->setUsername('user' . $i);
            $user->setEmail('email' . $i . '@gmail.com');
            $user->setPassword(password_hash('123', PASSWORD_DEFAULT));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        //Creation d'user admin

        $user = new User;
        $user->setUsername('Miguel');
        $user->setEmail('mikyfiestas@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(password_hash('123', PASSWORD_DEFAULT));
        $manager->persist($user);

        //Creation d'user especifique

        $user = new User;
        $user->setUsername('Miguel');
        $user->setEmail('miguelsj.pro@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(password_hash('123', PASSWORD_DEFAULT));
        $manager->persist($user);
        //Taches sans utilisateur
        for($i = 0; $i < 20; $i++){

            $task = new Task();
            $task->setTitle('Title' . $i);
            $task->setContent('Content' . $i);
            $task->setIsDone(false);
            $task->setCreatedAt((new \DateTimeImmutable('now')));
            $task->setUser(null);
            $manager->persist($task);
        }
        //Taches avec utilisateur

        for($i = 0; $i < 10; $i++){

            $task = new Task();
            $task->setTitle('Title' . $i);
            $task->setContent('Content' . $i);
            $task->setIsDone(false);
            $task->setCreatedAt((new \DateTimeImmutable('now')));
            $task->setUser($user);
            $manager->persist($task);
        }

        $manager->flush();
    }
}
