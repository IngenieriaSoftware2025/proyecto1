<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Aplicacion;

class AplicacionController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        // Verificar que esté logueado
        isAuth();
        
        $router->render('aplicacion/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            // Limpiar datos
            $_POST['app_nombre_largo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_largo']))));
            $_POST['app_nombre_medium'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_medium']))));
            $_POST['app_nombre_corto'] = strtoupper(trim(htmlspecialchars($_POST['app_nombre_corto'])));
            
            // Validaciones simples
            if (strlen($_POST['app_nombre_largo']) < 2) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Nombre largo muy corto']);
                exit;
            }
            
            if (strlen($_POST['app_nombre_medium']) < 2) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Nombre mediano muy corto']);
                exit;
            }
            
            if (strlen($_POST['app_nombre_corto']) < 2) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Nombre corto muy corto']);
                exit;
            }
            
            $_POST['app_fecha_creacion'] = '';
            $_POST['app_situacion'] = 1;
            
            $aplicacion = new Aplicacion($_POST);
            $resultado = $aplicacion->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode(['codigo' => 1, 'mensaje' => 'Aplicación guardada correctamente']);
            } else {
                http_response_code(500);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Error al guardar']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error interno']);
        }
    }

    public static function buscarAPI()
    {
        try {
            $sql = "SELECT * FROM aplicacion WHERE app_situacion = 1 ORDER BY app_fecha_creacion DESC";
            $data = self::fetchArray($sql);

            echo json_encode(['codigo' => 1, 'mensaje' => 'Aplicaciones encontradas', 'data' => $data]);

        } catch (Exception $e) {
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error al buscar']);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $id = $_POST['app_id'];
            $_POST['app_nombre_largo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_largo']))));
            $_POST['app_nombre_medium'] = ucwords(strtolower(trim(htmlspecialchars($_POST['app_nombre_medium']))));
            $_POST['app_nombre_corto'] = strtoupper(trim(htmlspecialchars($_POST['app_nombre_corto'])));

            $data = Aplicacion::find($id);
            $data->sincronizar([
                'app_nombre_largo' => $_POST['app_nombre_largo'],
                'app_nombre_medium' => $_POST['app_nombre_medium'],
                'app_nombre_corto' => $_POST['app_nombre_corto'],
                'app_situacion' => 1
            ]);
            $data->actualizar();

            echo json_encode(['codigo' => 1, 'mensaje' => 'Aplicación modificada correctamente']);
            
        } catch (Exception $e) {
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error al modificar']);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "UPDATE aplicacion SET app_situacion = 0 WHERE app_id = $id";
            self::SQL($sql);

            echo json_encode(['codigo' => 1, 'mensaje' => 'Aplicación eliminada correctamente']);
            
        } catch (Exception $e) {
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error al eliminar']);
        }
    }
}