<?php


namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Auth\Hashing\Hasher;
use App\Controllers\Controller;
use App\Models\User;
use App\Views\View;
use Doctrine\ORM\EntityManager;
use League\Route\Router;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class RegisterController extends Controller
{
    protected View $view;
    protected Hasher $hasher;
    protected EntityManager $db;
    protected Auth $auth;
    protected Router $router;

    public function __construct(View $view, Hasher $hasher, EntityManager $db, Auth $auth, Router $router)
    {
        $this->view = $view;
        $this->hasher = $hasher;
        $this->db = $db;
        $this->auth = $auth;
        $this->router = $router;
    }

    public function index()
    {
        $response = new Response();

        return $this->view->render($response, 'auth/register.twig');
    }

    public function register(RequestInterface $request)
    {
        $data = $this->validateRegistration($request);

        $user = $this->createUser($data);

        if (!$this->auth->attempt($data['name'], $data['password'])) {
            return redirect('/');
        }



        return redirect($this->router->getNamedRoute('home')->getPath());
    }

    protected function createUser(array $data)
    {
        $user = new User();

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->hasher->create($data['password'])
        ]);

        $this->db->persist($user);
        $this->db->flush();

        return $user;
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