<?php


namespace App\Controllers;

use App\Views\View;
use Zend\Diactoros\Response;

class HomeController
{
    protected View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index()
    {
        $response = new Response();

        return $this->view->render($response, 'home.twig');
    }
}