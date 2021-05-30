<?php


namespace App\Views;

use Twig\Environment;
use Zend\Diactoros\Response;

class View
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function make(string $view, array $data = [])
    {
        return $this->twig->render($view, $data);
    }

    public function render(Response $response, string $view, array $data = [])
    {
        $response->getBody()->write(
            $this->make($view, $data)
        );

        return $response;
    }

    public function share(array $data)
    {
        foreach ($data as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }
    }
}