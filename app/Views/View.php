<?php


namespace App\Views;

use Twig\Environment;
use Zend\Diactoros\Response;

class View
{
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(Response $response, string $view, array $data = [])
    {
        $response->getBody()->write(
            $this->twig->render($view, $data)
        );

        return $response;
    }
}