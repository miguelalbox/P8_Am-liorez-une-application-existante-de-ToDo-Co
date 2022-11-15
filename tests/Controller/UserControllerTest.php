<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
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

    //voir la liste de utilisateurs avec role admin
    public function testGetUsers()
    {
        $client = static::createClient();
        $user = $this->userRoleAdmin();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/users');

        //On verify que tout est bien passé
        $this->assertResponseIsSuccessful();
        //on verify que le button ajouter existe sur la page
        //$this->assertSelectorTextContains('button', 'Ajouter');
    }

    //voir la liste de utilisateurs avec role user
    public function testGetUsersError()
    {
        $client = static::createClient();
        $user = $this->userRoleUser();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/users');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    //voir la liste de utilisateurs avec role user
    public function testEditUsers()
    {
        $client = static::createClient();
        $user = $this->userRoleAdmin();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/users/9/edit');

        //On verify que tout est bien passé
        $this->assertResponseIsSuccessful();
        //on verify que le button ajouter existe sur la page
        //$this->assertSelectorTextContains('button', 'Ajouter');
    }
    //voir la liste de utilisateurs avec role user
    public function testDeleteUsers()
    {
        $client = static::createClient();
        $user = $this->userRoleAdmin();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/users/10/delete');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        //on verify que on redirectione vers users
        $this->assertResponseRedirects('/users');
    }


    //Homepage conecté

    public function testHomepageConnected()
    {
        $client = static::createClient();
        $user = $this->userRoleAdmin();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    //Homepage deconecté
    public function testHomepageDisconnected()
    {
        $client = static::createClient();

        //on se positionne sur l'url
        $crawler = $client->request('GET', '/');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        //on verify que on redirectione vers connexion
        $this->assertResponseRedirects('/connexion');

    }
}