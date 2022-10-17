<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/users', name: 'user_list')]
    public function listAction(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();

        return $this->render('user/list.html.twig', [
        'users' => $users
        ]);
    }



    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function editAction(User $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager, Request $request)
    {
        
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(), 
            'user' => $user
        ]);
    }
}
