<?php


namespace App\Controllers;


use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class HomeController
{
    public function index(ServerRequestInterface $request)
    {
        $response = new Response();

        $response->getBody()->write('Home');

        return $response;
    }
}