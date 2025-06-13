<?php

function debuguear($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html) {
    $s = htmlspecialchars($html);
    return $s;
}

function isAuth() {
    session_start();
    if(!isset($_SESSION['nombre'])) {
        $appName = $_ENV['APP_NAME'];
        header("Location: /$appName");
        exit;
    }
}

function isAuthApi() {
    getHeadersApi();
    session_start();
    if(!isset($_SESSION['nombre'])) {
        echo json_encode([    
            "mensaje" => "No está autenticado",
            "codigo" => 4,
        ]);
        exit;
    }
}

function hasPermission(array $permisos){
    session_start();
    
    error_log("DEBUG - Permisos requeridos: " . print_r($permisos, true));
    error_log("DEBUG - Sesión actual: " . print_r($_SESSION, true));
    
    $comprobaciones = [];
    
    foreach ($permisos as $permiso) {
        $comprobaciones[] = isset($_SESSION[$permiso]) ? true : false;
        error_log("DEBUG - Permiso '$permiso' en sesión: " . (isset($_SESSION[$permiso]) ? 'SÍ' : 'NO'));
    }

    error_log("DEBUG - Comprobaciones: " . print_r($comprobaciones, true));

    if(array_search(true, $comprobaciones) === false){
        error_log("DEBUG - ACCESO DENEGADO - Redirigiendo");
        $appName = $_ENV['APP_NAME'];
        header("Location: /$appName");
        exit;
    }
    
    error_log("DEBUG - ACCESO PERMITIDO");
}

function hasPermissionApi(array $permisos){
    getHeadersApi();
    session_start();
    $comprobaciones = [];
    
    foreach ($permisos as $permiso) {
        $comprobaciones[] = isset($_SESSION[$permiso]) ? true : false;
    }

    if(array_search(true, $comprobaciones) === false){
        echo json_encode([     
            "mensaje" => "No tiene permisos",
            "codigo" => 4,
        ]);
        exit;
    }
}

function getHeadersApi(){
    return header("Content-type:application/json; charset=utf-8");
}

function asset($ruta){
    return "/". $_ENV['APP_NAME']."/public/" . $ruta;
}