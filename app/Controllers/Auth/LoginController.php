<?php


namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Auth\Hashing\Hasher;
use App\Controllers\Controller;
use App\Views\View;
use League\Route\Router;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class LoginController extends Controller
{
    protected View $view;
    protected Auth $auth;
    protected Router $router;

    public function __construct(View $view, Auth $auth, Router $router)
    {
        $this->view = $view;
        $this->auth = $auth;
        $this->router = $router;
    }

    public function index()
    {
        $response = new Response();

        return $this->view->render($response, 'auth/login.twig');
    }

    public function login(RequestInterface $request)
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $attempt = $this->auth->attempt($data['email'], $data['password']);

        if (!$attempt) {
            dd('failed');
        }

        return redirect($this->router->getNamedRoute('home')->getPath());
    }
}