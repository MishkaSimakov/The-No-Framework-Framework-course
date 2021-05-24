<?php


namespace App\Controllers;

use App\Models\User;
use App\Views\View;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class HomeController
{
    protected View $view;

    public function __construct(View $view, EntityManager $db)
    {
        $this->db = $db;
        $this->view = $view;
    }

    public function index(ServerRequestInterface $request)
    {
        $response = new Response();

        $user = $this->db->getRepository(User::class)->find(1);

        return $this->view->render($response, 'home.twig', compact('user'));
    }
}