<?php


namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Views\View;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class RegisterController extends Controller
{
    protected View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index()
    {
        $response = new Response();

        return $this->view->render($response, 'auth/register.twig');
    }

    public function register(RequestInterface $request)
    {
        //
    }
}