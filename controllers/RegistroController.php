<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;

class RegistroController extends ActiveRecord
{
    public static function renderizarPagina(Router $router)
    {
        isAuth();
        hasPermission(['ADMIN', 'USUARIO_VER']); // ADMIN o ver usuarios
        
        $router->render('usuarios/index', []);
    }

    public static function guardarAPI()
    {
        isAuthApi();
        
        // Solo ADMIN puede crear usuarios
        if (!isset($_SESSION['ADMIN'])) {
            http_response_code(403);
            echo json_encode(['codigo' => 0, 'mensaje' => 'No tiene permisos para crear usuarios']);
            exit;
        }
        
        getHeadersApi();
    
        try {
            // Limpiar datos
            $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
            $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));
            $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
            $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
            $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
            $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));
            $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
            
            // Validaciones simples
            if (strlen($_POST['usuario_nom1']) < 2) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Nombre muy corto']);
                exit;
            }
            
            if (strlen($_POST['usuario_tel']) != 8) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Teléfono debe tener 8 dígitos']);
                exit;
            }
            
            if (strlen($_POST['usuario_dpi']) != 13) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'DPI debe tener 13 dígitos']);
                exit;
            }
            
            if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Correo no válido']);
                exit;
            }
            
            if (strlen($_POST['usuario_contra']) < 8) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Contraseña muy corta']);
                exit;
            }
            
            if ($_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Contraseñas no coinciden']);
                exit;
            }
            
            // Procesar foto
            $_POST['usuario_fotografia'] = '';
            if (isset($_FILES['usuario_fotografia']) && $_FILES['usuario_fotografia']['error'] === 0) {
                $dpi = $_POST['usuario_dpi'];
                $extension = pathinfo($_FILES['usuario_fotografia']['name'], PATHINFO_EXTENSION);
                $ruta = "storage/fotosUsuarios/$dpi.$extension";
                
                if (!file_exists(__DIR__ . "/../../storage/fotosUsuarios/")) {
                    mkdir(__DIR__ . "/../../storage/fotosUsuarios/", 0755, true);
                }
                
                if (move_uploaded_file($_FILES['usuario_fotografia']['tmp_name'], __DIR__ . "/../../" . $ruta)) {
                    $_POST['usuario_fotografia'] = $ruta;
                }
            }
            
            // Preparar datos
            $_POST['usuario_token'] = uniqid();
            $_POST['usuario_fecha_creacion'] = '';
            $_POST['usuario_fecha_contra'] = '';
            $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
            
            $usuario = new Usuarios($_POST);
            $resultado = $usuario->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode(['codigo' => 1, 'mensaje' => 'Usuario registrado correctamente']);
            } else {
                http_response_code(500);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Error al registrar']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error interno']);
        }
    }

    private static function procesarFotografia($archivo, $dpi)
    {
        $fileName = $archivo['name'];
        $fileTmpName = $archivo['tmp_name'];
        $fileSize = $archivo['size'];
        $fileError = $archivo['error'];
        
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        
        if (!in_array($fileExtension, $allowed)) {
            return ['error' => true, 'mensaje' => 'Solo se permiten archivos JPG, PNG o JPEG'];
        }
        
        if ($fileSize >= 2000000) {
            return ['error' => true, 'mensaje' => 'La imagen debe pesar menos de 2MB'];
        }
        
        if ($fileError === 0) {
            $ruta = "storage/fotosUsuarios/$dpi.$fileExtension";
            
            $directorioFotos = __DIR__ . "/../../storage/fotosUsuarios/";
            if (!file_exists($directorioFotos)) {
                mkdir($directorioFotos, 0755, true);
            }
            
            $subido = move_uploaded_file($archivo['tmp_name'], __DIR__ . "/../../" . $ruta);
            
            if ($subido) {
                return ['error' => false, 'ruta' => $ruta];
            } else {
                return ['error' => true, 'mensaje' => 'Error al subir la fotografía'];
            }
        } else {
            return ['error' => true, 'mensaje' => 'Error en la carga de fotografía'];
        }
    }

    public static function buscarAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["usuario_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "usuario_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "usuario_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM usuario WHERE $where ORDER BY usuario_fecha_creacion DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        isAuthApi();
        
        // Solo ADMIN puede modificar usuarios
        if (!isset($_SESSION['ADMIN'])) {
            http_response_code(403);
            echo json_encode(['codigo' => 0, 'mensaje' => 'No tiene permisos para modificar usuarios']);
            exit;
        }
        
        getHeadersApi();

        try {
            $id = $_POST['usuario_id'];
            
            // Aplicar las mismas validaciones que en guardar
            $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));
            $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));
            $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
            $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
            $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_VALIDATE_INT);
            $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));
            $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));
            $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);

            $data = Usuarios::find($id);
            $data->sincronizar([
                'usuario_nom1' => $_POST['usuario_nom1'],
                'usuario_nom2' => $_POST['usuario_nom2'],
                'usuario_ape1' => $_POST['usuario_ape1'],
                'usuario_ape2' => $_POST['usuario_ape2'],
                'usuario_tel' => $_POST['usuario_tel'],
                'usuario_direc' => $_POST['usuario_direc'],
                'usuario_dpi' => $_POST['usuario_dpi'],
                'usuario_correo' => $_POST['usuario_correo'],
                'usuario_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode(['codigo' => 1, 'mensaje' => 'Usuario modificado exitosamente']);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function EliminarAPI()
    {
        isAuthApi();
        
        // Solo ADMIN puede eliminar usuarios
        if (!isset($_SESSION['ADMIN'])) {
            http_response_code(403);
            echo json_encode(['codigo' => 0, 'mensaje' => 'No tiene permisos para eliminar usuarios']);
            exit;
        }
        
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            // Eliminación lógica
            $sql = "UPDATE usuario SET usuario_situacion = 0 WHERE usuario_id = $id";
            self::SQL($sql);

            http_response_code(200);
            echo json_encode(['codigo' => 1, 'mensaje' => 'Usuario eliminado correctamente']);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}