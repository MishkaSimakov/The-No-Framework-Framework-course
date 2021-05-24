<?php


namespace App\Controllers;

use App\Auth\Auth;
use App\Views\View;
use Zend\Diactoros\Response;

class HomeController
{
    protected View $view;
    protected Auth $auth;

    public function __construct(View $view, Auth $auth)
    {
        $this->view = $view;
        $this->auth = $auth;
    }

    public function index()
    {
        $response = new Response();

        return $this->view->render($response, 'home.twig', [
            'user' => $this->auth->user()
        ]);
    }
}