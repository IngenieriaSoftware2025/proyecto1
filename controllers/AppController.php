<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class AppController
{
    public static function index(Router $router)
    {
        // Cuando alguien vaya a la página principal, lo mandamos al login
        $router->render('login/index', [], 'layouts/layoutLogin');
    }

    public static function login()
    {
        // Limpiar cualquier output previo
        ob_start();
        
        try {
            // Validar datos
            if (!isset($_POST['usu_codigo']) || !isset($_POST['usu_password'])) {
                ob_clean();
                header("Content-type: application/json; charset=utf-8");
                echo json_encode(['codigo' => 0, 'mensaje' => 'Datos incompletos']);
                exit;
            }

            $usuario = trim($_POST['usu_codigo']);
            $contrasena = trim($_POST['usu_password']);

            if (empty($usuario) || empty($contrasena)) {
                ob_clean();
                header("Content-type: application/json; charset=utf-8");
                echo json_encode(['codigo' => 0, 'mensaje' => 'Usuario y contraseña requeridos']);
                exit;
            }

            // Buscar usuario - con manejo de errores del ActiveRecord
            $sql = "SELECT usuario_id, usuario_nom1, usuario_contra 
                    FROM usuario 
                    WHERE usuario_dpi = '$usuario' AND usuario_situacion = 1";
            
            // Capturar cualquier warning/notice del ActiveRecord
            $usuarios = @ActiveRecord::fetchArray($sql);
            
            if (empty($usuarios)) {
                ob_clean();
                header("Content-type: application/json; charset=utf-8");
                echo json_encode(['codigo' => 0, 'mensaje' => 'Usuario no encontrado']);
                exit;
            }

            $usuarioData = $usuarios[0];
            
            // Verificar contraseña
            if (!password_verify($contrasena, $usuarioData['usuario_contra'])) {
                ob_clean();
                header("Content-type: application/json; charset=utf-8");
                echo json_encode(['codigo' => 0, 'mensaje' => 'Contraseña incorrecta']);
                exit;
            }

            // Login exitoso
            @session_start();
            
            $_SESSION['nombre'] = $usuarioData['usuario_nom1'];
            $_SESSION['dpi'] = $usuario;
            $_SESSION['usuario_id'] = $usuarioData['usuario_id'];

            // Cargar permisos - también con manejo de errores
            $sqlPermisos = "SELECT permiso_clave as permiso 
                           FROM asig_permisos 
                           INNER JOIN permiso ON asignacion_permiso_id = permiso_id 
                           WHERE asignacion_usuario_id = " . $usuarioData['usuario_id'] . " 
                           AND asignacion_situacion = 1";

            $permisos = @ActiveRecord::fetchArray($sqlPermisos);
            
            if (!empty($permisos) && is_array($permisos)) {
                foreach ($permisos as $permiso) {
                    if (isset($permiso['permiso'])) {
                        $_SESSION[$permiso['permiso']] = 1;
                    }
                }
            }

            // Limpiar buffer y enviar respuesta JSON
            ob_clean();
            header("Content-type: application/json; charset=utf-8");
            echo json_encode(['codigo' => 1, 'mensaje' => 'Login exitoso']);
            exit;

        } catch (Exception $e) {
            ob_clean();
            header("Content-type: application/json; charset=utf-8");
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error interno']);
            exit;
        }
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        
        $appName = $_ENV['APP_NAME'];
        header("Location: /$appName");
        exit;
    }

    public static function renderInicio(Router $router)
    {
        // Solo usuarios con permiso ADMIN pueden ver el inicio
        // hasPermission(['ADMIN']); // ← Comentamos esto temporalmente
        
        $router->render('pages/index', [], 'layouts/layout');
    }
}