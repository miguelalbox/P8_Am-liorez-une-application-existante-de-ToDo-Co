<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for($i = 0; $i < 20; $i++){
        
            $task = new Task();
            $task->setTitle('Title' . $i);
            $task->setContent('Content' . $i);
            $task->setIsDone(false);
            $task->setCreatedAt((new \DateTimeImmutable('now')));
            $manager->persist($task);
        }

        for($i = 0; $i < 10; $i++){
            $user = new User;
            $user->setUsername('user' . $i);
            $user->setEmail('email' . $i . '@gmail.com');
            $user->setPassword(password_hash('123', PASSWORD_DEFAULT));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
