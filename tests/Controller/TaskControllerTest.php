<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
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
    //Creer une tache
    public function testCreateTask()
    {
        $client = static::createClient();
        $user = $this->userRoleUser();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/create');

        $submitButton = $crawler->selectButton('Ajouter');
        $form = $submitButton->form();

        $form["task[title]"] = "Tache modifie";
        $form["task[content]"] = "contenu modifie";
        $form["task[isDone]"] = false;

        //Soumettre le formulaire
        $client->submit($form);
        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }
    //modifier une tache
    public function testEditTask()
    {
        $client = static::createClient();
        $user = $this->userRoleUser();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/30/edit');

        $submitButton = $crawler->selectButton('Modifier');
        $form = $submitButton->form();

        $form["task[title]"] = "Tache modifie";
        $form["task[content]"] = "contenu modifie";
        $form["task[isDone]"] = true;

        //Soumettre le formulaire
        $client->submit($form);
        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        //$this->assertResponseIsSuccessful();
        //on verify que on redirectione vers users

    }
    //suprimer une tache
    public function testDeleteTask()
    {
        $client = static::createClient();
        $user = $this->userRoleUser();
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/30/delete');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        //on verify que on redirectione vers users
        $this->assertResponseRedirects('/task');
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

//Demarer les fixtures
    /*public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        //$this->databaseTool->loadFixtures();
        //parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testIndex()
    {
        // If you need a client, you must create it before loading fixtures because
        // creating the client boots the kernel, which is used by loadFixtures
        $client = $this->createClient();
        // add all your fixtures classes that implement
        // Doctrine\Common\DataFixtures\FixtureInterface
        $this->databaseTool->loadFixtures([
            'Bamarni\MainBundle\DataFixtures\ORM\LoadData',
            'Me\MyBundle\DataFixtures\ORM\LoadData'
        ]);

        // you can now run your functional tests with a populated database
        // ...
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }*/

}