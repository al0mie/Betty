<?php

namespace App\Controllers;

/**
 * Class Controller
 * @package App\Controllers
 */
class Controller
{
    /**
     * Base path for views
     *
     * @var string
     */
    protected $namespace = '../resources/views/';

    /**
     * Return required view
     *
     * @param $view
     * @return mixed
     */
    protected function view($view)
    {
        return require ($this->namespace . $view . '.php');
    }
}