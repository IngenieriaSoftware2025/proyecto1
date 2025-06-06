<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Exception;


class RegistroController extends ActiveRecord

{
    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/index', []);
    }
}