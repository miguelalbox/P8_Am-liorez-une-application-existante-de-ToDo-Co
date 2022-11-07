<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    public function userRoleUser(): User
    {

        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'miguelsj.pro@gmail.com']);
        return $user;

    }

    public function userRoleAdmin(): User
    {

        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'mikyfiestas@gmail.com']);
        return $user;

    }

    //Voir une tache a faire qui me partien
    public function testGetTaskIncompleteConnected()
    {
        $client = static::createClient();
        $user = $this->userRoleUser();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task');

        //On verify que tout est bien passé
        $this->assertResponseIsSuccessful();
        //on verify que le button ajouter existe sur la page
        //$this->assertSelectorTextContains('button', 'Ajouter');
    }

    //voir de taches a faire si je ne suis pas connectée
    public function testGetTaskIncompleteDisconnected()
    {
        $client = static::createClient();

        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task');

        //On verify que tout est bien passé et on est redirigé vers connexion
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/connexion');

    }

    //Voir une tache finis qui me partien
    public function testGetTaskDoneConnected()
    {
        $client = static::createClient();
        $user = $this->userRoleUser();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/done');

        //On verify que tout est bien passé
        $this->assertResponseIsSuccessful();
        //on verify que le button ajouter existe sur la page
        //$this->assertSelectorTextContains('button', 'Ajouter');
    }

    //voir de taches a faire si je ne suis pas connectée
    public function testGetTaskDoneDisconnected()
    {
        $client = static::createClient();

        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/done');

        //On verify que tout est bien passé et on est redirigé vers connexion
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/connexion');

    }

    //Voir une tache a faire qui partien a personne
    public function testGetTaskAnonymeIncompleteConnected()
    {
        $client = static::createClient();
        $user = $this->userRoleAdmin();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/anonyme');

        //On verify que tout est bien passé
        $this->assertResponseIsSuccessful();
        //on verify que le button ajouter existe sur la page
        //$this->assertSelectorTextContains('button', 'Ajouter');
    }

    //voir de taches a faire qui partien a personne si je ne suis pas connectée
    public function testGetTaskAnonymeIncompleteDisconnected()
    {
        $client = static::createClient();

        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/anonyme');

        //On verify que tout est bien passé et on est redirigé vers connexion
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/connexion');

    }

}