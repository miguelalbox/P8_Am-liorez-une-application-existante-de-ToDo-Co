<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task_list')]
    public function listAction(TaskRepository $taskRepo): Response
    {

        $user = $this->getUser();

        $tasks = $taskRepo->findAll();



        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'user' => $user,
        ]);
    }
    #[Route('/task/done', name: 'task_list_done')]
    public function listDoneAction(TaskRepository $taskRepo): Response
    {

        $user = $this->getUser();

        $task = $taskRepo->findAll();

        return $this->render('task/list-done.html.twig', [
            'tasks' => $task,
            'user' => $user,
        ]);
    }

    #[Route('/task/create', name: 'task_create')]
    public function createAction(Request $request, EntityManagerInterface $manager)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setUser($this->getUser());
            $task->setCreatedAt((new \DateTimeImmutable('now')));
            $manager->persist($task);
            $manager->flush();
            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/task/{id}/edit', name: 'task_edit')]
    public function editAction(Request $request, EntityManagerInterface $manager, Task $task)
    {
        if ($task->getUser() != $this->getUser()) {
            $this->addFlash('error', 'La tâche ne vous partiens pas.');
            return $this->redirectToRoute('task_list');
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($task);
            $manager->flush();
            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/togle', name: 'task_toggle')]
    public function toggleTaskAction(EntityManagerInterface $manager, Task $task)
    {
        if ($task->getUser() != $this->getUser()) {
            $this->addFlash('error', 'La tâche ne vous partiens pas.');
            return $this->redirectToRoute('task_list');
        }

        $task->toggle(!$task->isIsDone());

        $manager->flush();
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        if ($task->isIsDone() == true){
            return $this->redirectToRoute('task_list');
        }
        else{
            return $this->redirectToRoute('task_list_done');
        }

    }


    #[Route('/task/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(EntityManagerInterface $manager, Task $task)
    {
        if ($task->getUser() != $this->getUser()) {
            $this->addFlash('error', 'La tâche ne vous partiens pas.');
            return $this->redirectToRoute('task_list');
        }

        $user = $this->getUser();

        $tasks = $user->removeTask($task);
        $manager->flush();
        $this->addFlash('success', 'La tâche a bien été supprimée.');


        return $this->redirectToRoute('task_list');

    }

//TODO gestion de task sans utilisateur

    #[Route('/task/anonyme', name: 'task_list_anonyme')]
    public function listAnonymeAction(TaskRepository $taskRepo): Response
    {

        $task = $taskRepo->findBy(['user' => null]);

        return $this->render('task/list-anonyme.html.twig', [
            'tasks' => $task,
        ]);
    }


    #[Route('/task/anonyme/{id}/edit', name: 'task_edit_anonyme')]
    public function editAnonymeAction(Request $request, EntityManagerInterface $manager, Task $task)
    {

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list_anonyme');
        }

        return $this->render('task/edit-anonyme.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/task/anonyme/{id}/togle', name: 'task_toggle_anonyme')]
    public function toggleTaskAnonymeAction(EntityManagerInterface $manager, Task $task)
    {


        $task->toggle(!$task->isIsDone());

        $manager->flush();
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list_anonyme');
    }


    #[Route('/task/anonyme/{id}/delete', name: 'task_delete_anonyme')]
    public function deleteTaskAnonymeAction(EntityManagerInterface $manager, Task $task)
    {

        $manager->remove($task);
        $manager->flush();
        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list_anonyme');

    }

}