<?php


namespace App\Controllers;

use App\Auth\Auth;
use App\Cookie\CookieJar;
use App\Views\View;
use Zend\Diactoros\Response;

class HomeController
{
    protected View $view;
    protected CookieJar $cookie;

    public function __construct(View $view, CookieJar $cookie)
    {
        $this->view = $view;
        $this->cookie = $cookie;
    }

    public function index()
    {
        $this->cookie->set('abc', '123');

        $response = new Response();

        return $this->view->render($response, 'home.twig');
    }
}