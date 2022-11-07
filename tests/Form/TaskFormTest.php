<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskFormTest extends WebTestCase
{
    public function userRoleUser() :User
    {

        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'miguelsj.pro@gmail.com']);
        return $user;

    }
    //Tester que je peut me connnecter
    public function testShowTaskFormAsUser( )
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/create');

        //On verify que tout est bien passé
        $this->assertResponseIsSuccessful();
        //on verify que le button ajouter existe sur la page
        $this->assertSelectorTextContains('button', 'Ajouter');


    }
    //Tester que je ne peut pas voir la page si je ne suis pas connecter
    public function testShowTaskFormNotConnected()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/create');
        //Verification que on a reçu un code de redirection
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/connexion');
    }

    public function testFormAddTask()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/create');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Ajouter');
        $form = $submitButton->form();

        $form["task[title]"] = "Tache";
        $form["task[content]"] = "contenu";
        $form["task[isDone]"] = false;

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task');

    }

    //Modification d'une tache
    public function testFormEditTask()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/49/edit');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Modifier');
        $form = $submitButton->form();

        $form["task[title]"] = "Tache modifie";
        $form["task[content]"] = "contenu modifie";
        $form["task[isDone]"] = false;

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task');
    }

    //Tester que je peut change le status d'une task a terminé
    public function testTaskUserComplete( )
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Marquer comme faite');

        $form = $submitButton->form();

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task');

    }

    //Tester que je peut suprimer une task a terminé
    public function testTaskUserDelete( )
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Supprimer');

        $form = $submitButton->form();

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task');

    }


}