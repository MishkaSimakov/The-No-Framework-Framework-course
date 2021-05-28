<?php


namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use Zend\Diactoros\Response;

class LogoutController extends Controller
{
    protected Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function logout()
    {
        $this->auth->logout();

        return redirect('/');
    }
}