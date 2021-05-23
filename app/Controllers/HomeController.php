<?php


namespace App\Controllers;


use App\Views\View;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class HomeController
{
    protected View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(ServerRequestInterface $request)
    {
        $response = new Response();

        return $this->view->render($response, 'home.twig', [
            'name' => 'Billy'
        ]);
    }
}