<?php


namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
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
        $data = $this->validateRegistration($request);
    }

    protected function validateRegistration(RequestInterface $request)
    {
        return $this->validate($request, [
            'email' => ['required', 'email', ['exists', User::class, ]],
            'name' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required', ['equals', 'password']],
        ]);
    }
}