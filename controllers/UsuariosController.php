<?php

namespace Controllers;

use MVC\Router;
use Model\Usuarios;

class UsuariosController
{
    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/lista', []);
    }

    public static function buscarAPI()
    {
        getHeadersApi();
        
        // Usar consulta SQL directa para evitar problemas con ActiveRecord
        $sql = "SELECT * FROM usuario WHERE usuario_situacion = 1";
        $usuarios = Usuarios::fetchArray($sql);
        
        // Procesar las fotos
        foreach($usuarios as &$usuario) {
            $fotografia = isset($usuario['usuario_fotografia']) ? $usuario['usuario_fotografia'] : '';
            
            if(!empty($fotografia)) {
                if(file_exists(__DIR__ . '/../public/' . $fotografia)) {
                    $usuario['foto_url'] = '/' . $_ENV['APP_NAME'] . '/' . $fotografia;
                } else {
                    $usuario['foto_url'] = null;
                }
            } else {
                $usuario['foto_url'] = null;
            }
        }
        
        http_response_code(200);
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Usuarios encontrados',
            'data' => $usuarios
        ]);
        exit;
    }

    public static function buscarPorDpiAPI()
    {
        getHeadersApi();
        
        if(empty($_POST['usuario_dpi'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI es obligatorio'
            ]);
            exit;
        }
        
        // Usar consulta SQL directa
        $sql = "SELECT * FROM usuario WHERE usuario_dpi = '" . $_POST['usuario_dpi'] . "' AND usuario_situacion = 1";
        $usuario = Usuarios::fetchFirst($sql);
        
        if(!$usuario) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Usuario no encontrado'
            ]);
            exit;
        }
        
        // Procesar foto
        $fotografia = isset($usuario['usuario_fotografia']) ? $usuario['usuario_fotografia'] : '';
        
        if(!empty($fotografia)) {
            if(file_exists(__DIR__ . '/../public/' . $fotografia)) {
                $usuario['foto_url'] = '/' . $_ENV['APP_NAME'] . '/' . $fotografia;
            } else {
                $usuario['foto_url'] = null;
            }
        } else {
            $usuario['foto_url'] = null;
        }
        
        http_response_code(200);
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Usuario encontrado',
            'data' => $usuario
        ]);
        exit;
    }

    public static function eliminarAPI()
    {
        getHeadersApi();
        
        $usuario = Usuarios::find($_POST['usuario_id']);
        
        if(!$usuario) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El usuario no existe'
            ]);
            exit;
        }
        
        $usuario->sincronizar(['usuario_situacion' => 0]);
        $resultado = $usuario->actualizar();
        
        if($resultado['resultado'] == 1) {
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuario eliminado correctamente'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el usuario'
            ]);
        }
        exit;
    }
}