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

        //TODO ici on affiches les taches null pour l'admin si il faut dfaire ça il faut ajouter dans le reste des routes la gestion
        /*$userRole = $this->getUser()->getRoles();

        if ($userRole == ("ROLE_ADMIN")){
            $task = $taskRepo->findBy(['user' => null]);

            return $this->render('task/list.html.twig', [
                'tasks' => $task,
            ]);
        }*/

        $user = $this->getUser()->getId();

        $task = $taskRepo->findBy(['user' => $user]);

        return $this->render('task/list.html.twig', [
            'tasks' => $task,
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
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

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

        return $this->redirectToRoute('task_list');
    }


    #[Route('/task/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(EntityManagerInterface $manager, Task $task)
    {
        if ($task->getUser() != $this->getUser()) {
            $this->addFlash('error', 'La tâche ne vous partiens pas.');
            return $this->redirectToRoute('task_list');
        }
        $manager->remove($task);
        $manager->flush();
        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');

}

    //TODO creer une route dedie a changer le user de taches sans user

    /*#[Route('/task/anonyme', name: 'task_anonyme')]
    public function anonymeTaskAction(TaskRepository $taskRepo)
    {
        $anonymes = $taskRepo->findBy(['user' => null]);

        $anonymeUser = 'anonyme';
        $anonyme = [];
        foreach ( $anonymes as $anonyme){
        $anonyme->setUser($anonymeUser);
    }
        dd($anonyme);
    }*/
}
