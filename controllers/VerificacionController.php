<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Exception;


class VerificaionController extends ActiveRecord

{
    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/index', []);
    }
}