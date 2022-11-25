<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserFormTest extends WebTestCase
{
    //Demarer les fixtures
    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            'App\DataFixtures\AppFixturesTest'
        ]);
        self::ensureKernelShutdown();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }

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
    //Verifier la connexion
    public function testConnexionTrue( )
    {
        $client = static::createClient();
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/connexion');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Sign in');
        $form = $submitButton->form();

        $form["username"] = "miguelsj.pro@gmail.com";
        $form["password"] = "123";

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/');
    }

    //Verifier error de connexion
    public function testConnexionFalse( )
    {
        $client = static::createClient();
        //On se positione sur connexion
        $crawler = $client->request('GET', '/connexion');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Sign in');
        $form = $submitButton->form();

        $form["username"] = "";
        $form["password"] = "";

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé avec un status 302
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    //Form Register

    //Creation de compte
    public function testCreateCompte( )
    {
        $client = static::createClient();
        //On se positione sur inscription
        $crawler = $client->request('GET', '/inscription');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Register');
        $form = $submitButton->form();

        $form["registration_form[username]"] = "Test";
        $form["registration_form[email]"] = "test6@test.fr";
        $form["registration_form[plainPassword]"] = "00000000";

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé avec un status 302
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
    //Admin user

    //creation d'utilisateur
    public function testFormCreateUser()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleAdmin();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/users/create');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Créer');
        $form = $submitButton->form();

        $form["registration_form[email]"] = "php14@php.php";
        $form["registration_form[username]"] = "php";
        $form["registration_form[plainPassword]"] = "000000";
        $form["role"] = "ROLE_USER";

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/users');
    }
    //edition d'utilisateur
    public function testFormEditUser()
    {
        $client = static::createClient();
        //ici on appelle la funcion userRoleUser avec le role user
        $user = $this->userRoleAdmin();
        //on login le client
        $client->loginUser($user);
        //on se positionne sur l'url
        $crawler = $client->request('GET', '/users/5/edit');

        //Recuperer le formulaire
        $submitButton = $crawler->selectButton('Modifier');
        $form = $submitButton->form();

        $form["registration_form[email]"] = "php@php.php";
        $form["registration_form[username]"] = "php";
        $form["registration_form[plainPassword]"] = "000000";
        $form["role"] = "ROLE_USER";

        //Soumettre le formulaire
        $client->submit($form);

        //On verify que tout est bien passé
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/users');
    }






}