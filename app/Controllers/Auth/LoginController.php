<?php


namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Views\View;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class LoginController extends Controller
{
    protected View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index()
    {
        $response = new Response();

        return $this->view->render($response, 'auth/login.twig');
    }

    public function login(RequestInterface $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }
}