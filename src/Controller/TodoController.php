<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if(!$session->has('todos')){
            $todos = array('achat'=> "acheter clé usb",
                'cours' => "finaliser mon cours",
                'correction'=>"corriger mes examens");
            $session->set('todos',$todos);
            $this->addFlash('inital list',"liste initialised successfully");
        }

        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }
    #[Route('/todo/add/{title?today}/{content?coding}', name: 'add_todo')]
    public function addToDo(Request $request,$title,$content):Response
    {
        $session = $request->getSession();
        if ($session->has('todos')){
            $todos=$session->get('todos');
            if(isset($todos[$title])){
                $this->addFlash('exist',"this todo '$title' already exists");
            }
            else{
                $todos[$title]=$content;
                $session->set('todos',$todos);
                $this->addFlash('added',"todo '$title' added successfully");
            }
        }
        else{
            $this->addFlash('error',"No todo list");
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/todo/reset', name: 'reset_todo')]
    public function reset(Request $request){
        $session = $request->getSession();
        if (!$session->has('todos')){
            $this->addFlash('error',"No todo list");
        }
        else{
            $session->set('todos',array('achat'=> "acheter clé usb",
                'cours' => "finaliser mon cours",
                'correction'=>"corriger mes examens"));
            $this->addFlash('reset',"todo reseted successfully !");
        }
        return $this->redirectToRoute('app_todo');
    }

#[Route('/todo/remove/{title}', name: 'remove_todo')]
public function remove(Request $request,$title){
        $session = $request->getSession();
    if (!$session->has('todos')){
        $this->addFlash('error',"No todo list");
    }
    else{
        $todos = $session->get('todos');
        if(isset($todos[$title])){
            unset($todos[$title]);
            $session->set('todos',$todos);
            $this->addFlash('remove',"todo '$title' removed successfully");
        }
        else{
            $this->addFlash('error',"todo '$title' not found");
        }
    }
    return $this->redirectToRoute('app_todo');
}
}