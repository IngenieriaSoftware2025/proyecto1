<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Exception;


class LoginController extends ActiveRecord

{
    public static function renderizarPagina(Router $router)
    {
        $router->render('login/index', [],  'layouts/layoutLogin');
    }
}