<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;
use Exception;


class RegistroController extends ActiveRecord

{
    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $db = Usuarios::getDB();
        $db->beginTransaction();


        //saniticacion de nombre y validaccion con capital
        $_POST['usuario_nom1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom1']))));

        $cantidad_nombre = strlen($_POST['usuario_nom1']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_nom2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nom2']))));

        $cantidad_nombre = strlen($_POST['usuario_nom2']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        //saniticacion de apellido y validaccion con capital
        $_POST['usuario_ape1'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape1']))));
        $cantidad_apellido = strlen($_POST['usuario_ape1']);

        if ($cantidad_apellido < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }
        //saniticacion de apellido y validaccion con capital
        $_POST['usuario_ape2'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_ape2']))));
        $cantidad_apellido = strlen($_POST['usuario_ape2']);

        if ($cantidad_apellido < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Nombre debe de tener mas de 1 caracteres'
            ]);
            return;
        }

        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_tel']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El telefono debe de tener 8 numeros'
            ]);
            return;
        }

        $_POST['usuario_direc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_direc']))));


        $_POST['usuario_dpi'] = filter_var($_POST['usuario_dpi'], FILTER_SANITIZE_NUMBER_INT);
        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El cui debe ser de 13 digitos'
            ]);
            return;
        }

        $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electronico no es valido'
            ]);
        }

        // VALIDACIÓN: Contraseña
        if (strlen($_POST['usuario_contra']) < 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe tener al menos 8 caracteres'
            ]);
            return;
        }

        // VALIDACIÓN: Confirmar contraseña
        if ($_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
            return;
        }
        //VALIDACION DE EL TOKEN
        $_POST['usuario_token'] = uniqid();

        //VALIDACION DE LA FECHA DE CREACION
        $_POST['usuario_fecha_creacion'] = date('Y-m-d H:i:s', strtotime($_POST['usuario_fecha_creacion']));


        //VALIDACION DE AL FECHA DE  CONTRA
        $_POST['usuario_fecha_contra'] = date('Y-m-d H:i:s', strtotime($_POST['usuario_fecha_contra']));

        // Proceso la fotografía
        $rutaFotografia = '';

        if (isset($_FILES['usuario_fotografia']) && !empty($_FILES['usuario_fotografia']['name'])) {

            $file = $_FILES['usuario_fotografia'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            $dpi = $_POST['usuario_dpi'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Extensiones permitidas
            $allowed = ['jpg', 'jpeg', 'png'];
            if (!in_array($fileExtension, $allowed)) {

                //Pongo que no lo guarde si hay error
                $db->rollBack();

                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Solo puede cargar archivos JPG, PNG o JPEG',
                ]);
                return;
            }

            if ($fileSize >= 2000000) {
                //Pongo que no lo guarde si hay error
                $db->rollBack();

                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La imagen debe pesar menos de 2MB',
                ]);
                return;
            }

            if ($fileError === 0) {
                $rutaFotografia = "storage/fotosUsuarios/$dpi.$fileExtension";
                $subido = move_uploaded_file($fileTmpName, __DIR__ . "/../../" . $rutaFotografia);
                if (!$subido) {
                    //Pongo que no lo guarde si hay error
                    $db->rollBack();
                    http_response_code(500);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => "No se subió el archivo: " . $fileName,
                    ]);
                    return;
                }
            } else {
                //Pongo que no lo guarde si hay error
                $db->rollBack();
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error en la carga de fotografía',
                ]);
                return;
            }
        }

        //se envian los datos a guardar despues de sanitizar
        try {
            $usuario = new Usuarios(
                [
                    'usuario_nom1' => $_POST['usuario_nom1'],
                    'usuario_nom2' => $_POST['usuario_nom2'],
                    'usuario_ape1' => $_POST['usuario_ape1'],
                    'usuario_ape2' => $_POST['usuario_ape2'],
                    'usuario_tel' => $_POST['usuario_tel'],
                    'usuario_direc' => $_POST['usuario_direc'],
                    'usuario_dpi' => $_POST['usuario_dpi'],
                    'usuario_correo' => $_POST['usuario_correo'],
                    'usuario_contra' => $_POST['usuario_contra'],
                    'usuario_token' => $_POST['usuario_token'],
                    'usuario_fecha_creacion' => $_POST['usuario_fecha_creacion'],
                    'usuario_fecha_contra' => $_POST['usuario_fecha_contra'],
                    'usuario_fotografia' => $_POST['usuario_fotografia'],
                    'usuario_fotografia' => $_POST['usuario_fotografia'],
                    'usuario_situacion' => 1

                ]
            );

            $crear = $usuario->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Exito al guardar usuario'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Erro al guardar cliente',
                'detalle' => $e->getMessage()
            ]);
            return;
        }
    }
}
