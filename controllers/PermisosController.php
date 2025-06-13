<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;

class PermisosController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        isAuth();
        
        // Solo ADMIN puede gestionar permisos
        if (!isset($_SESSION['ADMIN'])) {
            $appName = $_ENV['APP_NAME'];
            header("Location: /$appName");
            exit;
        }
        
        $router->render('permisos/index', []);
    }

    public static function buscarUsuariosAPI()
    {
        // Verificar que esté logueado via API
        isAuthApi();
        
        try {
            $sql = "SELECT usuario_id, usuario_nom1, usuario_nom2, usuario_ape1, usuario_ape2, usuario_dpi 
                    FROM usuario 
                    WHERE usuario_situacion = 1 
                    ORDER BY usuario_nom1";
            
            $usuarios = self::fetchArray($sql);
            
            // Verificar si hay usuarios
            if (empty($usuarios)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'No hay usuarios registrados',
                    'data' => []
                ]);
                exit;
            }
            
            // Formatear nombres completos
            $usuariosFormateados = [];
            foreach ($usuarios as $usuario) {
                $usuariosFormateados[] = [
                    'usuario_id' => $usuario['usuario_id'] ?? '',
                    'nombre_completo' => trim(($usuario['usuario_nom1'] ?? '') . ' ' . ($usuario['usuario_nom2'] ?? '') . ' ' . ($usuario['usuario_ape1'] ?? '') . ' ' . ($usuario['usuario_ape2'] ?? '')),
                    'usuario_dpi' => $usuario['usuario_dpi'] ?? ''
                ];
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios encontrados',
                'data' => $usuariosFormateados
            ]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar usuarios',
                'detalle' => $e->getMessage()
            ]);
            exit;
        }
    }

    public static function buscarAplicacionesAPI()
    {
        try {
            $sql = "SELECT app_id, app_nombre_largo, app_nombre_medium, app_nombre_corto 
                    FROM aplicacion 
                    WHERE app_situacion = 1 
                    ORDER BY app_nombre_largo";
            
            $aplicaciones = self::fetchArray($sql);
            
            // Verificar si hay aplicaciones
            if (empty($aplicaciones)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'No hay aplicaciones registradas',
                    'data' => []
                ]);
                exit;
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicaciones encontradas',
                'data' => $aplicaciones
            ]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar aplicaciones',
                'detalle' => $e->getMessage()
            ]);
            exit;
        }
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            // DEBUG: Ver qué datos llegan
            error_log("POST recibido: " . print_r($_POST, true));
            
            $usuario_id = filter_var($_POST['usuario_id'], FILTER_SANITIZE_NUMBER_INT);
            $app_id = filter_var($_POST['app_id'], FILTER_SANITIZE_NUMBER_INT);
            $permiso_clave = strtoupper(trim(htmlspecialchars($_POST['permiso_clave'])));
            $permiso_desc = ucfirst(strtolower(trim(htmlspecialchars($_POST['permiso_desc']))));
            $tipo_permiso = strtoupper(trim(htmlspecialchars($_POST['tipo_permiso'])));
            $motivo = ucfirst(strtolower(trim(htmlspecialchars($_POST['motivo']))));
            $usuario_asigna = $_SESSION['usuario_id'] ?? 1;

            error_log("Datos procesados - Usuario: $usuario_id, App: $app_id, Permiso: $permiso_clave");

            // Validaciones simples
            if (empty($usuario_id) || empty($app_id) || empty($permiso_clave)) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Datos incompletos']);
                exit;
            }

            // CREAR PERMISO DIRECTAMENTE SIN VERIFICAR SI EXISTE
            $sqlPermiso = "INSERT INTO permiso (permiso_app_id, permiso_nombre, permiso_clave, permiso_desc, permiso_situacion) 
                          VALUES ($app_id, '$permiso_clave', '$permiso_clave', '$permiso_desc', 1)";
            
            error_log("SQL Permiso: " . $sqlPermiso);
            self::SQL($sqlPermiso);
            
            // Obtener el ID del último permiso insertado
            $ultimoId = self::$db->lastInsertId();
            error_log("Último ID insertado: " . $ultimoId);

            // Asignar permiso
            $sqlAsignar = "INSERT INTO asig_permisos (
                asignacion_usuario_id, 
                asignacion_app_id, 
                asignacion_permiso_id, 
                asignacion_usuario_asigno, 
                asignacion_motivo, 
                asignacion_situacion
            ) VALUES (
                $usuario_id, 
                $app_id, 
                $ultimoId, 
                $usuario_asigna, 
                '$motivo', 
                1
            )";

            error_log("SQL Asignar: " . $sqlAsignar);
            self::SQL($sqlAsignar);

            http_response_code(200);
            echo json_encode(['codigo' => 1, 'mensaje' => 'Permiso asignado correctamente']);
            exit;

        } catch (Exception $e) {
            error_log("Error en guardarAPI: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error interno', 'detalle' => $e->getMessage()]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        try {
            $sql = "SELECT 
                        ap.asignacion_id,
                        u.usuario_nom1, 
                        u.usuario_ape1,
                        u.usuario_dpi,
                        a.app_nombre_corto,
                        p.permiso_clave,
                        p.permiso_desc,
                        ap.asignacion_motivo
                    FROM asig_permisos ap
                    INNER JOIN usuario u ON ap.asignacion_usuario_id = u.usuario_id
                    INNER JOIN aplicacion a ON ap.asignacion_app_id = a.app_id
                    INNER JOIN permiso p ON ap.asignacion_permiso_id = p.permiso_id
                    WHERE ap.asignacion_situacion = 1
                    ORDER BY ap.asignacion_id DESC";

            $permisos = self::fetchArray($sql);

            // Verificar si hay permisos
            if (empty($permisos)) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'No hay permisos asignados',
                    'data' => []
                ]);
                exit;
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos encontrados',
                'data' => $permisos
            ]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error al buscar permisos', 'detalle' => $e->getMessage()]);
            exit;
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        try {
            // Por simplicidad, solo permitir cambiar motivo
            $asignacion_id = filter_var($_POST['asignacion_id'], FILTER_SANITIZE_NUMBER_INT);
            $motivo = ucfirst(strtolower(trim(htmlspecialchars($_POST['motivo']))));

            $sql = "UPDATE asig_permisos SET asignacion_motivo = '$motivo' WHERE asignacion_id = $asignacion_id";
            self::SQL($sql);

            http_response_code(200);
            echo json_encode(['codigo' => 1, 'mensaje' => 'Permiso modificado correctamente']);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error al modificar']);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "UPDATE asig_permisos SET asignacion_situacion = 0 WHERE asignacion_id = $id";
            self::SQL($sql);

            http_response_code(200);
            echo json_encode(['codigo' => 1, 'mensaje' => 'Permiso eliminado correctamente']);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error al eliminar']);
        }
    }
}