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

    public function userRoleAdmin() :User
    {

        $container = static::getContainer();
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'mikyfiestas@gmail.com']);
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
        $crawler = $client->request('GET', '/task/31/edit');

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
    //Modification d'une tache qui me partien pas
    public function testFormEditOtherTask()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/20/edit');


        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task');
    }

    //Tester que je peut change le status d'une task a terminé
    public function testTaskUserComplete()
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
    //Tester que je peut change le status d'une task a pas terminé
    public function testTaskUserIncomplete()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/done');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Marquer non terminée');

        $form = $submitButton->form();

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task/done');

    }
    //Tester que je peut change le status d'une task a terminé
    public function testTaskOtherUserComplete()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/20/togle');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task');

    }

    //Tester que je peut suprimer une task
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
    //Tester que je ne peut pas suprimer une task d'autre user
    public function testTaskOtherUserDelete( )
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/20/delete');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task');

    }


    //Modification d'une tache
    public function testFormEditAnonymeTask()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleAdmin();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/anonyme/5/edit');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Modifier');
        $form = $submitButton->form();

        $form["task[title]"] = "Tache modifie Anonyme";
        $form["task[content]"] = "contenu modifie Anonyme";
        $form["task[isDone]"] = false;

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task/anonyme');
    }

    //Modification d'une tache anonyme sans le droits
    public function testFormEditAnonymeTaskRoleUser()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/anonyme/5/edit');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    //Tester que je peut change le status d'une task anonyme a terminé
    public function testTaskUserAnonymeComplete( )
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleAdmin();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/anonyme');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Marquer comme faite');

        $form = $submitButton->form();

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task/anonyme');

    }
    //Tester que je peut change le status d'une task anonyme a terminé autant que user sans privilege
    public function testTaskUserAnonymeCompleteRoleUser( )
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/anonyme/5/togle');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }

    //Tester que je peut suprimer une task anonyme
    public function testTaskUserAnonymeDelete( )
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleAdmin();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/anonyme');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Supprimer');

        $form = $submitButton->form();

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/task/anonyme');

    }

    //Tester que je peut pas suprimer une task anonyme sans droits admin
    public function testTaskUserAnonymeDeleteRoleUser( )
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleUser();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/task/anonyme/5/delete');

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }


}