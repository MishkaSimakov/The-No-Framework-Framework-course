<?php


namespace App\Controllers;


use App\Models\Post;
use App\Views\View;
use Zend\Diactoros\Response;

class PostController
{
    protected View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index()
    {
        $response = new Response();

        $posts = Post::paginate(3);

        return $this->view->render($response, 'posts/index.twig', compact('posts'));
    }
}