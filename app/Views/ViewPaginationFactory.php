<?php

namespace App\Views;

class ViewPaginationFactory
{
    protected View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function make($view, $data = [])
    {
        return $this->view->make($view, $data);
    }
}